<!DOCTYPE html>


<html lang="es">

        @include('parts.head')

<body>

@include('parts.header')




        <div id="cont_principal">
            <div class="container-fluid" id="container_a">
                
            @include('parts.nav')

                <div class="row">

                    <div class="col-sm">
                            <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel" style="position:relative;">
                                <div class="carousel-inner">
                                    <!-- Slide 1 -->
                                    <div class="carousel-item active" data-bs-interval="10000">
                                        <img src="images/sistema_c1.jpeg" class="d-block w-100" alt="Conducción Responsable">
                                        <div class="capa_carrousel">
                                            <div class="contenido_carrousel">
                                                <div class="titulo_carrousel">Programa de Conducción Responsable</div>
                                                <div class="descriptores_carrousel">Gobierno Federal<span class="puntos_descriptores"><i class="fas fa-circle" style="vertical-align:middle;"></i></span><span>2025</span><span class="puntos_descriptores"><i class="fas fa-circle" style="vertical-align:middle;"></i></span><span>Activo</span></div>
                                                <div>
                                                    <div class="class_tags_carrousel">
                                                        <span class="etiqueta_cinta_carrousel tag_item_carrousel">Seguridad Vial</span>
                                                        <span class="etiqueta_cinta_carrousel tag_item_carrousel">Recompensas</span>
                                                        <span class="etiqueta_cinta_carrousel tag_item_carrousel">Puntos</span>
                                                    </div>
                                                </div>
                                                <div class="parrafo_carrousel">
                                                    <p>Forma parte del programa nacional que reconoce y premia a los conductores que respetan los límites de velocidad. Acumula puntos por tu conducción responsable y canjéalos por beneficios exclusivos. Juntos construimos vías más seguras para todos.</p>
                                                </div>
                                                <div>
                                                    <button class="boton_ver_album boton_ver_edicion">
                                                        <svg width="1.5rem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.603 3.113A1.125 1.125 0 0 1 9 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113Z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span style="vertical-align:middle;">Conocer más</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Slide 2 -->
                                    <div class="carousel-item" data-bs-interval="10000">
                                        <img src="images/sistema_c2.jpeg" class="d-block w-100" alt="Canje de Recompensas">
                                        <div class="capa_carrousel">
                                            <div class="contenido_carrousel">
                                                <div class="titulo_carrousel">Canje de Recompensas</div>
                                                <div class="descriptores_carrousel">Beneficios<span class="puntos_descriptores"><i class="fas fa-circle" style="vertical-align:middle;"></i></span><span>2025</span><span class="puntos_descriptores"><i class="fas fa-circle" style="vertical-align:middle;"></i></span><span>Disponible</span></div>
                                                <div>
                                                    <div class="class_tags_carrousel">
                                                        <span class="etiqueta_cinta_carrousel tag_item_carrousel">Descuentos</span>
                                                        <span class="etiqueta_cinta_carrousel tag_item_carrousel">Gasolina</span>
                                                        <span class="etiqueta_cinta_carrousel tag_item_carrousel">Servicios</span>
                                                    </div>
                                                </div>
                                                <div class="parrafo_carrousel">
                                                    <p>Tus puntos tienen valor. Canjéalos por descuentos en gasolina, mantenimiento vehicular, descuentos en verificación, y más beneficios. Mientras más respetes los límites de velocidad, más recompensas obtienes. Tu seguridad es nuestro compromiso.</p>
                                                </div>
                                                <div>
                                                    <button class="boton_ver_album boton_ver_edicion">
                                                        <svg width="1.5rem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.603 3.113A1.125 1.125 0 0 1 9 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113Z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span style="vertical-align:middle;">Ver beneficios</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Slide 3 -->
                                    <div class="carousel-item" data-bs-interval="10000">
                                        <img src="images/carrusel3.jpg" class="d-block w-100" alt="Rankings Nacional">
                                        <div class="capa_carrousel">
                                            <div class="contenido_carrousel">
                                                <div class="titulo_carrousel">Ranking Nacional de Conductores</div>
                                                <div class="descriptores_carrousel">Competencia<span class="puntos_descriptores"><i class="fas fa-circle" style="vertical-align:middle;"></i></span><span>2025</span><span class="puntos_descriptores"><i class="fas fa-circle" style="vertical-align:middle;"></i></span><span>En Curso</span></div>
                                                <div>
                                                    <div class="class_tags_carrousel">
                                                        <span class="etiqueta_cinta_carrousel tag_item_carrousel">Ranking</span>
                                                        <span class="etiqueta_cinta_carrousel tag_item_carrousel">Premios</span>
                                                        <span class="etiqueta_cinta_carrousel tag_item_carrousel">Reconocimientos</span>
                                                    </div>
                                                </div>
                                                <div class="parrafo_carrousel">
                                                    <p>Compite con conductores de todo México. Consulta tu posición en el ranking nacional y estatal. Los mejores conductores del mes reciben reconocimientos especiales y premios adicionales. Demuestra que eres un conductor ejemplar y representa a tu estado.</p>
                                                </div>
                                                <div>
                                                    <button class="boton_ver_album boton_ver_edicion">
                                                        <svg width="1.5rem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.603 3.113A1.125 1.125 0 0 1 9 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113Z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span style="vertical-align:middle;">Ver ranking</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <style>
                            /* Colores del gobierno mexicano - tonalidad vino/guinda */
                            .tag_item_carrousel {
                                background-color: #6B1C3D !important; /* Color vino guinda */
                                color: #FFFFFF !important;
                                padding: 0.3rem 0.8rem;
                                border-radius: 0.3rem;
                                font-size: 0.85rem;
                                font-weight: 500;
                                margin-right: 0.5rem;
                            }

                            .titulo_carrousel {
                                color: #FFFFFF !important;
                                font-weight: 700;
                                text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
                            }

                            .descriptores_carrousel {
                                color: #F0F0F0 !important;
                            }

                            .parrafo_carrousel {
                                color: #EFEFEF !important;
                            }

                            .boton_ver_album.boton_ver_edicion {
                                background-color: #6B1C3D !important; /* Color vino guinda */
                                color: #FFFFFF !important;
                                border: none;
                                padding: 0.7rem 1.5rem;
                                border-radius: 0.4rem;
                                font-weight: 600;
                                transition: all 0.3s ease;
                            }

                            .boton_ver_album.boton_ver_edicion:hover {
                                background-color: #8B2450 !important; /* Tono más claro al hover */
                                transform: scale(1.05);
                            }

                            .puntos_descriptores {
                                color: #6B1C3D;
                                margin: 0 0.5rem;
                            }
                            </style>

                            <button class="carousel-control-prev boton_carrusel_izquierda" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                                <i class="fas fa-chevron-left" style="color: #a7acd1; font-size: 1rem;"></i>
                            </button>

                            <button class="carousel-control-next boton_carrusel_derecha" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                                <i class="fas fa-chevron-right" style="color: #a7acd1; font-size: 1rem;"></i>
                            </button>

                            

                        </div>


                    </div>

                </div>


<!-- Sección 1: Estadísticas Destacadas -->
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <h2 class="text-center mb-5" style="color: #6B1C3D; font-weight: 700;">Programa en Números</h2>
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-users" style="font-size: 3rem; color: #6B1C3D;"></i>
                        <h3 class="mt-3" style="color: #6B1C3D; font-weight: 700;">125,847</h3>
                        <p class="text-muted">Conductores Registrados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-gift" style="font-size: 3rem; color: #6B1C3D;"></i>
                        <h3 class="mt-3" style="color: #6B1C3D; font-weight: 700;">89,234</h3>
                        <p class="text-muted">Recompensas Canjeadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-shield-alt" style="font-size: 3rem; color: #6B1C3D;"></i>
                        <h3 class="mt-3" style="color: #6B1C3D; font-weight: 700;">32</h3>
                        <p class="text-muted">Estados Participantes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-chart-line" style="font-size: 3rem; color: #6B1C3D;"></i>
                        <h3 class="mt-3" style="color: #6B1C3D; font-weight: 700;">-42%</h3>
                        <p class="text-muted">Reducción de Incidentes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección 2: ¿Cómo Funciona? -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5" style="color: #6B1C3D; font-weight: 700;">¿Cómo Funciona el Programa?</h2>
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <div class="mb-3" style="background-color: #6B1C3D; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-user-plus" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h5 style="color: #6B1C3D; font-weight: 600;">1. Regístrate</h5>
                    <p class="text-muted">Crea tu cuenta con tu licencia de conducir y datos vehiculares</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <div class="mb-3" style="background-color: #6B1C3D; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-tachometer-alt" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h5 style="color: #6B1C3D; font-weight: 600;">2. Conduce Responsable</h5>
                    <p class="text-muted">Respeta los límites de velocidad en todo momento</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <div class="mb-3" style="background-color: #6B1C3D; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-star" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h5 style="color: #6B1C3D; font-weight: 600;">3. Acumula Puntos</h5>
                    <p class="text-muted">Gana puntos automáticamente por tu buena conducción</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <div class="mb-3" style="background-color: #6B1C3D; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-trophy" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h5 style="color: #6B1C3D; font-weight: 600;">4. Canjea Beneficios</h5>
                    <p class="text-muted">Usa tus puntos en descuentos y recompensas exclusivas</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección 3: Beneficios Destacados -->
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <h2 class="text-center mb-5" style="color: #6B1C3D; font-weight: 700;">Beneficios Disponibles</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-gas-pump mb-3" style="font-size: 2.5rem; color: #6B1C3D;"></i>
                        <h5 style="color: #6B1C3D; font-weight: 600;">Descuento en Gasolina</h5>
                        <p class="text-muted">Hasta 15% de descuento en estaciones participantes</p>
                        <span class="badge" style="background-color: #6B1C3D;">Desde 500 puntos</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-tools mb-3" style="font-size: 2.5rem; color: #6B1C3D;"></i>
                        <h5 style="color: #6B1C3D; font-weight: 600;">Mantenimiento Vehicular</h5>
                        <p class="text-muted">Servicios de mantenimiento con descuento especial</p>
                        <span class="badge" style="background-color: #6B1C3D;">Desde 800 puntos</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-clipboard-check mb-3" style="font-size: 2.5rem; color: #6B1C3D;"></i>
                        <h5 style="color: #6B1C3D; font-weight: 600;">Verificación Vehicular</h5>
                        <p class="text-muted">Descuentos en tu verificación obligatoria anual</p>
                        <span class="badge" style="background-color: #6B1C3D;">Desde 300 puntos</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-shield-alt mb-3" style="font-size: 2.5rem; color: #6B1C3D;"></i>
                        <h5 style="color: #6B1C3D; font-weight: 600;">Seguros de Auto</h5>
                        <p class="text-muted">Tarifas preferenciales con aseguradoras aliadas</p>
                        <span class="badge" style="background-color: #6B1C3D;">Desde 1000 puntos</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-parking mb-3" style="font-size: 2.5rem; color: #6B1C3D;"></i>
                        <h5 style="color: #6B1C3D; font-weight: 600;">Estacionamientos</h5>
                        <p class="text-muted">Acceso a tarifas especiales en estacionamientos públicos</p>
                        <span class="badge" style="background-color: #6B1C3D;">Desde 400 puntos</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-car mb-3" style="font-size: 2.5rem; color: #6B1C3D;"></i>
                        <h5 style="color: #6B1C3D; font-weight: 600;">Accesorios Vehiculares</h5>
                        <p class="text-muted">Descuentos en tiendas de accesorios automotrices</p>
                        <span class="badge" style="background-color: #6B1C3D;">Desde 600 puntos</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <button class="btn btn-lg" style="background-color: #6B1C3D; color: white; padding: 0.8rem 2.5rem; font-weight: 600;">Ver Todos los Beneficios</button>
        </div>
    </div>
</section>

<!-- Sección 4: Llamado a la Acción -->
<section class="py-5" style="background: linear-gradient(135deg, #6B1C3D 0%, #8B2450 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="text-white mb-3" style="font-weight: 700;">¿Listo para ser parte del cambio?</h2>
                <p class="text-white" style="font-size: 1.1rem;">Únete a miles de conductores que ya están construyendo un México más seguro y responsable. Registra tu vehículo hoy y comienza a acumular puntos.</p>
            </div>
            <div class="col-md-4 text-center">
                <button class="btn btn-light btn-lg" style="padding: 1rem 2.5rem; font-weight: 600; color: #6B1C3D;">Registrarme Ahora</button>
            </div>
        </div>
    </div>
</section>

<!-- Sección 5: Información Adicional -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h4 style="color: #6B1C3D; font-weight: 600;" class="mb-3">
                            <i class="fas fa-question-circle me-2"></i>Preguntas Frecuentes
                        </h4>
                        <div class="mb-3">
                            <strong>¿Cómo se registran mis trayectos?</strong>
                            <p class="text-muted mb-2">El sistema registra automáticamente tus recorridos mediante sensores viales instalados en las principales carreteras del país.</p>
                        </div>
                        <div class="mb-3">
                            <strong>¿Cuánto tiempo tardan en acreditarse los puntos?</strong>
                            <p class="text-muted mb-2">Los puntos se acreditan en un periodo de 24 a 48 horas después de cada trayecto registrado.</p>
                        </div>
                        <a href="#" style="color: #6B1C3D; font-weight: 600;">Ver más preguntas →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h4 style="color: #6B1C3D; font-weight: 600;" class="mb-3">
                            <i class="fas fa-info-circle me-2"></i>Requisitos para Participar
                        </h4>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #6B1C3D;"></i>Licencia de conducir vigente</li>
                            <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #6B1C3D;"></i>Tarjeta de circulación actualizada</li>
                            <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #6B1C3D;"></i>Vehículo con placas mexicanas</li>
                            <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #6B1C3D;"></i>Verificación vehicular al corriente</li>
                            <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #6B1C3D;"></i>Mayor de 18 años</li>
                        </ul>
                        <a href="#" style="color: #6B1C3D; font-weight: 600;">Ver requisitos completos →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



            </div>


           

        </div>



    @include('parts.footer')
    @include('parts.scripts')
    

</body>


</html>