<?php

namespace App\Console\Commands;

use App\Models\MstFGs;
use App\Models\MstRawMaterials;
use App\Models\MstWips;
use App\Models\RecapStock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecapStockSOMCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recap-stock-s-o-m-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recap stock Start of Month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = date('Y-m');

        //for Finish Goods
        $type_product = 'FG';
        $fgdatas = MstFGs::where('status', 'Active')->get();
        foreach ($fgdatas as $fgdata) {
            $id_master_product = $fgdata->id;
            $qty_start = $fgdata->stock ?? 0;
            $weight_start = $fgdata->weight_stock ?? 0;
            
            // Cek apakah period sudah ada untuk produk ini
            $exists = RecapStock::where('period', $currentDate)
                ->where('type_product', $type_product)
                ->where('id_master_product', $id_master_product)
                ->exists();

            if (!$exists) {
                RecapStock::create([
                    'period' => $currentDate,
                    'type_product' => $type_product,
                    'id_master_product' => $id_master_product,
                    'qty_start' => $qty_start,
                    'weight_start' => $weight_start,
                ]);
            }
        }

        //for raw material
        $type_product = 'RM';
        $rmdatas = MstRawMaterials::where('status', 'Active')->get();
        foreach ($rmdatas as $rmdata) {
            $id_master_product = $rmdata->id;
            $qty_start = $rmdata->stock ?? 0;
            $weight_start = $rmdata->weight_stock ?? 0;
            
            // Cek apakah period sudah ada untuk produk ini
            $exists = RecapStock::where('period', $currentDate)
                ->where('type_product', $type_product)
                ->where('id_master_product', $id_master_product)
                ->exists();

            if (!$exists) {
                RecapStock::create([
                    'period' => $currentDate,
                    'type_product' => $type_product,
                    'id_master_product' => $id_master_product,
                    'qty_start' => $qty_start,
                    'weight_start' => $weight_start,
                ]);
            }
        }

        //for WIP
        $type_product = 'WIP';
        $wipdatas = MstWips::where('status', 'Active')->get();
        foreach ($wipdatas as $wipdata) {
            $id_master_product = $wipdata->id;
            $qty_start = $wipdata->stock ?? 0;
            $weight_start = $wipdata->weight_stock ?? 0;
            
            // Cek apakah period sudah ada untuk produk ini
            $exists = RecapStock::where('period', $currentDate)
                ->where('type_product', $type_product)
                ->where('id_master_product', $id_master_product)
                ->exists();

            if (!$exists) {
                RecapStock::create([
                    'period' => $currentDate,
                    'type_product' => $type_product,
                    'id_master_product' => $id_master_product,
                    'qty_start' => $qty_start,
                    'weight_start' => $weight_start,
                ]);
            }
        }

        //for TA
        $type_product = 'TA';
        $tadatas = DB::table('master_tool_auxiliaries')->get();
        foreach ($tadatas as $tadata) {
            $id_master_product = $tadata->id;
            $qty_start = $tadata->stock ?? 0;
            $weight_start = $tadata->weight_stock ?? 0;
            
            // Cek apakah period sudah ada untuk produk ini
            $exists = RecapStock::where('period', $currentDate)
                ->where('type_product', $type_product)
                ->where('id_master_product', $id_master_product)
                ->exists();

            if (!$exists) {
                RecapStock::create([
                    'period' => $currentDate,
                    'type_product' => $type_product,
                    'id_master_product' => $id_master_product,
                    'qty_start' => $qty_start,
                    'weight_start' => $weight_start,
                ]);
            }
        }
    }
}
