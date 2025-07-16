<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterSeccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('secciones', function (Blueprint $table) {            
            $table->unsignedBigInteger('id')->autoIncrement()->change();
            $table->text('descripcion')->nullable()->after('color');
            // $table->boolean('estado')->default(true)->after('name');
            $table->unsignedBigInteger('tipo_competencia_id')->nullable()->after('descripcion');

            $table->foreign('tipo_competencia_id')->references('id')->on('tipo_competencias');
        });

        // Actualizar registros existentes (1 al 18)
        $secciones = [
            1 => ['descripcion' => 'Es capaz de organizar, gestionar, dirigir, prever, coordinar y controlar las funciones asignadas.', 'tipo_competencia_id' => 4],
            2 => ['descripcion' => 'Es la capacidad de velocidad, sentido de urgencia, esfuerzo, involucramiento y compromiso por cumplir los objetivos planteados en búsqueda de resultados exitosos.', 'tipo_competencia_id' => 4],
            3 => ['descripcion' => 'Capacidad de comunicarse de manera fluida, hacerse escuchar y persistencia en mantener sus puntos de vista, a fin de lograr soluciones negociadas; sin perder sus objetivos esenciales.', 'tipo_competencia_id' => 3],
            4 => ['descripcion' => 'Es la comunicación asertiva y activa que persigue trasmitir igualdad entre los interlocutores, horizontalidad, sensaciones que motiven a los usuarios a comunicar y relacionarse, favoreciendo la implicación y la modificación de actitudes y conductas.', 'tipo_competencia_id' => 2],
            6 => ['descripcion' => 'Es el valor que muestra la persona al conjunto de recursos, procedimientos, estrategias, documentos y estructura organizacional, con el objetivo de mejorar aquellos elementos de la organización que influyen en el logro de los resultados deseados por la misma.', 'tipo_competencia_id' => 5],
            8 => ['descripcion' => 'Es capaz de regular sus sentimientos de acuerdo a cada ocasión.', 'tipo_competencia_id' => 2],
            9 => ['descripcion' => 'Capacidad de colaborar de manera efectiva con los miembros del equipo hacia un objetivo en común.', 'tipo_competencia_id' => 5],
            10 => ['descripcion' => 'Constancia y capacidad para lograr nuestros objetivos.', 'tipo_competencia_id' => 1],
            12 => ['descripcion' => 'Pasión por el esfuerzo adicional y dar lo mejor de uno mismo.', 'tipo_competencia_id' => 1],
            13 => ['descripcion' => 'Saber reconocer los sentimientos y actitudes de las demás personas y aceptarlos.', 'tipo_competencia_id' => 1],
            14 => ['descripcion' => 'Persona capaz de liderar, dirigir, convocar, transmitir una actitud y conducta activa; fijando metas con claridad y optimismo, evidenciando conductas de apoyo a los miembros del equipo.', 'tipo_competencia_id' => 3],
            15 => ['descripcion' => 'Persona con capacidad de dirigir, convocar, atraer e influir en el accionar de otros individuos.', 'tipo_competencia_id' => 3],
            16 => ['descripcion' => 'Persona que elabora, elige, fija las misiones y objetivos en su área de trabajo, determinando qué tareas hacer, quién las hace, cómo se agrupan y dónde se toman las decisiones, con el objetivo de cumplir metas a visión.', 'tipo_competencia_id' => 4],
            18 => ['descripcion' => 'Es la elección después de un análisis entre las opciones o formas para resolver diferentes situaciones considerando la experiencia y el buen juicio en busca de un logro de resultado nuevo y útil.', 'tipo_competencia_id' => 3],
        ];

        foreach ($secciones as $id => $data) {
            DB::table('secciones')
                ->where('id', $id)
                ->update([
                    'descripcion' => $data['descripcion'],
                    'tipo_competencia_id' => $data['tipo_competencia_id'],
                ]);
        }

        // Insertar nuevos registros (19 en adelante)
        DB::table('secciones')->insert([
            [
                'name' => 'Integridad',
                'color' => '#568BA5 ',
                'descripcion' => '',
                'tipo_competencia_id' => 1,
                'estado' => true,
            ],
            [
                'name' => 'Comunicación efectiva',
                'color' => '#568BA5 ',
                'descripcion' => 'Es la comunicación asertiva y activa que persigue trasmitir igualdad entre los interlocutores, horizontalidad, sensaciones que motiven a los usuarios a comunicar y relacionarse, favoreciendo la implicación y la modificación de actitudes y conductas.',
                'tipo_competencia_id' => 2,
                'estado' => true,
            ],
            [
                'name' => 'Proactividad',
                'color' => '#568BA5 ',
                'descripcion' => '',
                'tipo_competencia_id' => 2,
                'estado' => true,
            ],
            [
                'name' => 'Inteligencia emocional',
                'color' => '#568BA5 ',
                'descripcion' => 'Es capaz de regular sus sentimientos de acuerdo a cada ocasión.',
                'tipo_competencia_id' => 2,
                'estado' => true,
            ],
            [
                'name' => 'Visión integral del negocio',
                'color' => '#568BA5 ',
                'descripcion' => '',
                'tipo_competencia_id' => 3,
                'estado' => true,
            ],
            [
                'name' => 'Accountability',
                'color' => '#568BA5 ',
                'descripcion' => '',
                'tipo_competencia_id' => 4,
                'estado' => true,
            ],
            [
                'name' => 'Calidad del trabajo',
                'color' => '#568BA5 ',
                'descripcion' => 'Es el valor que muestra la persona al conjunto de recursos, procedimientos, estrategias, documentos y estructura organizacional, con el objetivo de mejorar aquellos elementos de la organización que influyen en el logro de los resultados deseados por la misma.',
                'tipo_competencia_id' => 5,
                'estado' => true,
            ],
            [
                'name' => 'Desarrollo de relaciones',
                'color' => '#568BA5 ',
                'descripcion' => '',
                'tipo_competencia_id' => null,
                'estado' => true,
            ],
            [
                'name' => 'Innovación',
                'color' => '#568BA5 ',
                'descripcion' => '',
                'tipo_competencia_id' => null,
                'estado' => true,
            ],
            [
                'name' => 'Toma de decisiones críticas',
                'color' => '#568BA5 ',
                'descripcion' => 'Es la elección después de un análisis entre las opciones o formas para resolver diferentes situaciones considerando la experiencia y el buen juicio en busca de un logro de resultado nuevo y útil.',
                'tipo_competencia_id' => 3,
                'estado' => true,
            ],
            [
                'name' => 'Negociación y gestión de acuerdos',
                'color' => '#568BA5 ',
                'descripcion' => 'Capacidad de comunicarse de manera fluida, hacerse escuchar y persistencia en mantener sus puntos de vista, a fin de lograr soluciones negociadas; sin perder sus objetivos esenciales.',
                'tipo_competencia_id' => 3,
                'estado' => true,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('secciones', function (Blueprint $table) {
            $table->dropForeign(['tipo_competencia_id']);
            $table->dropColumn('descripcion');
            $table->dropColumn('tipo_competencia_id');
        });
    }
}