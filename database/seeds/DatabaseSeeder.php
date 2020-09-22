<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(MesSeeder::class);
         $this->call(RolSeeder::class);
         $this->call(UserSeeder::class);
         $this->call(GradoSeeder::class);
         $this->call(CursoSeeder::class);
         $this->call(SeccionSeeder::class);
         $this->call(DepartamentoSeeder::class);
         $this->call(MunicipioSeeder::class);
         $this->call(InstitucionSeeder::class);
         $this->call(NivelEducativoSeeder::class);
         $this->call(AlumnoSeeder::class);
         $this->call(CicloSeeder::class);
         $this->call(InscripcionSeeder::class);
         $this->call(ConceptoPagoSeeder::class);
    }
}
