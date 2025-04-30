<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'id' => 1,
                'order' => 10,
                'title' => 'Inicio',
                'to' => 'Home',
                'icon' => 'tabler-home',
                'requiredPermission' => 'menu.home',
            ],
            [
                'id' => 2,
                'order' => 20,
                'title' => 'Compañias',
                'to' => 'Company-List',
                'icon' => ' tabler-building',
                'requiredPermission' => 'company.list',
            ],
            [
                'id' => 3,
                'order' => 30,
                'title' => 'Usuarios',
                'to' => 'User-List',
                'icon' => 'tabler-user',
                'requiredPermission' => 'menu.user',
            ],
            [
                'id' => 4,
                'order' => 40,
                'title' => 'Roles',
                'to' => 'Role-List',
                'icon' => 'tabler-shield',
                'requiredPermission' => 'menu.role',
            ],
            [
                'id' => 5,
                'order' => 50,
                'title' => 'Prestadores',
                'to' => 'ServiceVendor-List',
                'icon' => 'tabler-building-hospital',
                'requiredPermission' => 'serviceVendor.list',
            ],
            [
                'id' => 6,
                'order' => 60,
                'title' => 'Entidades',
                'to' => 'Entity-List',
                'icon' => 'tabler-align-box-bottom-center',
                'requiredPermission' => 'menu.entity',
            ],
        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $key => $value) {
            $data = Menu::find($value['id']);
            if (! $data) {
                $data = new Menu;
            }
            $data->id = $value['id'];
            $data->order = $value['order'];
            $data->title = $value['title'];
            $data->to = $value['to'] ?? null;
            $data->icon = $value['icon'] ?? null;
            $data->father = $value['father'] ?? null;
            $data->requiredPermission = $value['requiredPermission'] ?? null;
            $data->heading = $value['heading'] ?? false;
            $data->save();
        }

        $bar->finish(); // Finalizar la barra
    }
}
