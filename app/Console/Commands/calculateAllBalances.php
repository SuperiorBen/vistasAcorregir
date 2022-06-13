<?php

namespace App\Console\Commands;

use App\Models\Recargas\Parciales;
use App\Models\Recargas\Totales;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class calculateAllBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chivo:calculateAllBalances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate all balances';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Pulling everyone balances since the dawn of time');


        $date = new DateTime('2021-12-16');

        $date_fmt = $date->format('Y-m-d');

        echo $date_fmt . PHP_EOL;

        $this->pullPartitalBalance($date_fmt);

        return Command::SUCCESS;
    }

    private function pullPartitalBalance($date)
    {

        $query = "
        select cc.id, cc.dni, SUM(balance.total) total, mc.id currency_id from
        (
            select tt.commerce_id as xid, 1 as ordering, 'tt' as source, tt.fiat_amount_to_commerce as total, tt.currency_id FROM transactions_transaction tt where tt.status = 'approved' and tt.fiat_amount_to_commerce > 0 and modified < '{$date}'
            union all
            select mw.commerce_id, 2, 'mw', (mw.amount * -1), mw.currency_id from money_withdrawals mw WHERE mw.status not in ('rejected','cancelled') and mw.amount > 0 and modified < '{$date}'
            union all
            select mt.commerce_to_id, 3, 'mt', (mt.amount *  1), mt.currency_id from money_transfer mt WHERE mt.status not in ('cancelled') and mt.amount > 0 and modified < '{$date}'
            union all
            select mt.commerce_from_id, 4, 'mt', (mt.amount * -1), mt.currency_id from money_transfer mt WHERE mt.status not in ('cancelled') and mt.amount > 0 and modified < '{$date}'
            union all
            select md.commerce_id, 5, 'md', (md.amount *  1), md.currency_id from money_deposit md WHERE md.status in ('approved') and md.amount > 0 and modified < '{$date}'
            union all
            select me.commerce_id, 6, 'me', (me.amount * -1), me.currency_from_id FROM money_exchange me WHERE me.status in ('approved') and me.total > 0 and modified < '{$date}'
            union all
            select me.commerce_id, 7, 'me', (me.total *   1), me.currency_to_id FROM money_exchange me WHERE me.status in ('approved') and me.total > 0 and modified < '{$date}'
            union all
            select mp.commerce_id, 8, 'mp', (mp.amount * -1), mp.currency_id FROM money_purchase mp WHERE mp.status in ('initial', 'approved') and mp.amount > 0 and modified < '{$date}'
            order by ordering
        ) balance
        join commerces_commerce cc on cc.id = balance.xid
        join money_currency mc on mc.id = balance.currency_id
        group by cc.dni, cc.id, mc.id";

        $query = DB::raw($query);

        $partials = DB::select($query);

        echo ' - total ' . count($partials) . PHP_EOL;

        $i = 0;
        $totales = [];

        foreach ($partials as $partial) {
            try {
                $total = [];
                $total['dni'] = $partial->dni;
                $total['cc_id'] = $partial->id;
                $total['moneda_id'] = $partial->currency_id;
                $total['total'] = $partial->total;
                $total['fecha'] = $date;
                $totales[] = $total;

                $i++;

                if ($i % 500 === 0) {
                    echo '.';
                    Totales::insertOrIgnore($totales);
                    $totales = [];
                    $i = 0;
                }
            } catch(\Illuminate\Database\QueryException $ex){ 
                $this->error($ex->getMessage());
            }
        }
        Totales::insertOrIgnore($totales);
        echo PHP_EOL;
    }
}
