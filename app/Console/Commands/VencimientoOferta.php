<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Oferta;

class VencimientoOferta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vencimiento:ofertas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se desactiva una oferta cuando esta vence';

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
     * @return mixed
     */
    public function handle()
    {
        $ofertas = Oferta::all();
        $auxFecha = Carbon::now('-5:00');
        DB::beginTransaction();
        foreach ($ofertas as $oferta){
            if (!empty($oferta->fecha_cierre) && !empty($oferta->fecha_publicacion) && 
                $oferta->estado_proceso != 'Finalizada con contratación' && 
                $oferta->estado_proceso != 'Finalizada sin contratación' &&
                $oferta->estado_proceso != 'Pendiente'){
                if ($auxFecha->gt((Carbon::parse($oferta->fecha_cierre))->addDay())) {
                  $oferta->update(['estado_proceso' => 'Expirada']);    
                }
            }
        }
        DB::commit();
    }
}
