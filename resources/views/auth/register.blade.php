@extends('layouts.master-without-nav')
@section('title')
    @lang('translation.signup')
@endsection
@section('content')

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="index" class="d-inline-block auth-logo">
                                    <h3 class="text-white">SIGA-CLUB</h3>
                            </a>
                        </div>
                            <p class="mt-3 fs-15 fw-medium">La mejor opción para la gestión.</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Crea una nueva cuenta</h5>
                                    <p class="text-muted">Obten acceso al sistema.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form class="needs-validation" novalidate method="POST"
                                        action="{{ route('register') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">Correo electronico <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}" id="useremail"
                                                placeholder="ejemplo@correo.com" required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Por favor introduce el correo electronico.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Nombre <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" value="{{ old('name') }}" id="username"
                                                placeholder="Nombre del usuario" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Por favor introduce el nombre.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="apaterno" class="form-label">Apellido Paterno <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('apaterno') is-invalid @enderror"
                                                name="apaterno" value="{{ old('apaterno') }}" id="apaterno"
                                                placeholder="Apellido Paterno" required>
                                            @error('apaterno')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Por favor introduce el apellido paterno.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="amaterno" class="form-label">Apellido Materno <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('amaterno') is-invalid @enderror"
                                                name="amaterno" value="{{ old('amaterno') }}" id="amaterno"
                                                placeholder="Apellido Materno" required>
                                            @error('amaterno')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Por favor introduce el apellido materno.
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label for="genero" class="form-label">Genero <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select @error('genero') is-invalid @enderror" name="genero"
                                                id="genero" required>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                                <option value="No binario">No binario</option>
                                                <option value="Otro">No especificar</option>
                                            </select>
                                            @error('genero')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Por favor introduce el genero.
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label for="fechaNac" class="form_label"> Fecha de nacimiento <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('fechaNac') is-invalid @enderror"
                                                name="fechaNac" value="{{ old('fechaNac') }}" id="fechaNac" required>
                                            @error('fechaNac')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Por favor introduce la fecha de nacimiento.
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label for="userpassword" class="form-label">Contraseña <span
                                                    class="text-danger">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                id="userpassword" placeholder="Contraseña" required>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Por favor introduce la contraseña.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="input-password">Confirma la contraseña <span
                                                    class="text-danger">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation" id="input-password"
                                                placeholder="Confirma la Contraseña" required>

                                            <div class="form-floating-icon">
                                                <i data-feather="lock"></i>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <input hidden type="text" class="form-control" name="rol" value="colaborador">
                                        </div>

                                        <div class=" mb-4">
                                            <label for="input-avatar">Foto de perfil <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                                name="avatar" id="input-avatar" required>
                                            @error('avatar')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="">
                                                <i data-feather="file"></i>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <p class="mb-0 fs-12 text-muted fst-italic">Al registrarte aceptas los <a href="#"
                                                    class="text-primary text-decoration-underline fst-normal fw-medium">Terminos de uso</a></p>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Registrarse</button>
                                        </div>


                                        <!--
                                        <div class="mt-4 text-center">
                                            <div class="signin-other-title">
                                                <h5 class="fs-13 mb-4 title text-muted">Create account with</h5>
                                            </div>

                                            <div>
                                                <button type="button"
                                                    class="btn btn-primary btn-icon waves-effect waves-light"><i
                                                        class="ri-facebook-fill fs-16"></i></button>
                                                <button type="button"
                                                    class="btn btn-danger btn-icon waves-effect waves-light"><i
                                                        class="ri-google-fill fs-16"></i></button>
                                                <button type="button"
                                                    class="btn btn-dark btn-icon waves-effect waves-light"><i
                                                        class="ri-github-fill fs-16"></i></button>
                                                <button type="button"
                                                    class="btn btn-info btn-icon waves-effect waves-light"><i
                                                        class="ri-twitter-fill fs-16"></i></button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">¿Ya tienes cuenta? <a href="auth-signin-basic"
                                    class="fw-semibold text-primary text-decoration-underline"> Inicia sesión </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> SIGA-CLUB. <i
                                    class="mdi mdi-heart text-danger"></i></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/particles.js/particles.js.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/particles.app.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>
@endsection
