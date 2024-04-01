<template>
    <div class=" flex-col w-full min-h-[calc(100vh-5rem)] p-16">
        <div class="p-4 mb-6">
            <h1 class="text-4xl font-bold">Horarios</h1>

        </div>
        <div class="w-full  bg-white border border-gris-borde p-8 rounded-t-2xl">
            <Link :href="route('nuevoHorario')"
                class=" bg-azul-fondo-btn rounded-xl h-min w-max flex justify-center items-center hover:bg-azul-fondo-btn-hover active:bg-azul-fondo-btn-pulse">
            <p class=" text-nowrap mx-2 text-lg text-white p-1"><span
                    class=" text-2xl font-bold mr-2 text-blue-100">+</span>A&ntilde;adir horario</p>
            </Link>
        </div>
        <div class="border border-gris-borde w-full rounded-b-xl">
            <div>
                <p class=" text-xl xl:text-2xl font-bold p-4">Lista de horarios</p>
                <div>
                    <div class=" bg-white rounded-b-xl p-3 flex flex-col gap-5 max-w-6xl w-full">
                        <div class="flex flex-col">
                            <p class=" text-lg ml-2">Horario Basico</p>
                            <div class="pl-4 flex flex-col sm:flex-row sm:gap-10 flex-wrap ">
                                <div class="sm:w-56">
                                    <p>Entrada: 08:00</p>
                                    <p>Salida: 18:30</p>
                                </div>
                                <div class="sm:w-56">
                                    <p>Descanso salida: 14:00</p>
                                    <p>Descanso entrada: 15:00</p>
                                </div>
                                <div class="sm:w-56">
                                    <p>Libre: 30 minutos</p>
                                    <div class="flex gap-1  justify-end mr-5 sm:mr-24">
                                        <div class="h-6 w-6">
                                            <img class=" w-full h-full" src="img/iconos/editar.png" alt="Icono editar"
                                                title="Editar">
                                        </div>
                                        <div class="h-6 w-6">
                                            <img class=" w-full h-full" src="img/iconos/borrar.png" alt="Icono borrar"
                                                title="Borrar">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class=" border-b border-gris-borde w-11/12 self-center mt-3"></div>
                        </div>
                        <div class="flex flex-col">
                            <p class=" text-lg ml-2">Horario Basico2</p>
                            <div class="pl-4 flex flex-col sm:flex-row sm:gap-10 flex-wrap ">
                                <div class="sm:w-56">
                                    <p>Entrada: 08:00</p>
                                    <p>Salida: 18:30</p>
                                </div>
                                <div class="sm:w-56">
                                    <p>Descanso salida: 14:00</p>
                                    <p>Descanso entrada: 15:00</p>
                                </div>
                                <div class="sm:w-56">
                                    <p>Libre: 30 minutos</p>
                                    <div class="flex gap-1  justify-end mr-5 sm:mr-24">
                                        <div class="h-6 w-6">
                                            <img class=" w-full h-full" src="img/iconos/editar.png" alt="Icono editar"
                                                title="Editar">
                                        </div>
                                        <div class="h-6 w-6">
                                            <img class=" w-full h-full" src="img/iconos/borrar.png" alt="Icono borrar"
                                                title="Borrar">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class=" border-b border-gris-borde w-11/12 self-center mt-3"></div>
                        </div>
                        <div class="flex flex-col">
                            <p class=" text-lg ml-2">Horario Flexible</p>
                            <div class="pl-4 flex flex-col sm:flex-row sm:gap-10 flex-wrap ">
                                <div class="sm:w-56">
                                    <p>Entrada: No definido</p>
                                    <p>Salida: No definido</p>
                                </div>
                                <div class="sm:w-56">
                                    <p>Descanso salida: No definido</p>
                                    <p>Descanso entrada: No definido</p>
                                </div>
                                <div class="sm:w-56">
                                    <p>Libre: No definido</p>
                                    <div class="flex gap-1  justify-end mr-5 sm:mr-24">
                                        <div class="h-6 w-6">
                                            <img class=" w-full h-full" src="img/iconos/editar.png" alt="Icono editar"
                                                title="Editar">
                                        </div>
                                        <div class="h-6 w-6">
                                            <img class=" w-full h-full" src="img/iconos/borrar.png" alt="Icono borrar"
                                                title="Borrar">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class=" border-b border-gris-borde w-11/12 self-center mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>