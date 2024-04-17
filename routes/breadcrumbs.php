<?php
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('showUsers', function ($trail) {
    $trail->push('Empleados', route('showUsers'));
});

Breadcrumbs::for('showUser', function ($trail) {
    $trail->parent('showUsers');
    $trail->push('Perfil empleado', route('showUser'));
});

Breadcrumbs::for('horarios', function ($trail) {
    $trail->push('Horarios', route('horarios'));
});

Breadcrumbs::for('editHorario', function ($trail) {
    $trail->parent('horarios');
    $trail->push('Edita horario', route('editaHorario',1));
});

Breadcrumbs::for('newHorario', function ($trail) {
    $trail->parent('horarios');
    $trail->push('Nuevo horario', route('editaHorario',1));
});