@extends('auth.layouts.app')

@section('content')
    <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-8 col-lg-12 col-md-9">
        
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                    <br>
                    <div class="text-center">
                        <img src="{{ url('backend/img/logo.jpg') }}" id="logo" style="height: 80px;">
                        <br>
                        <br>
                    </div>
                    @if(Request::segment(2) == 'success')
                      <div class="alert alert-success" role="alert">
                          La contraseña ha sido actualizada con éxito. Por favor ingrese nuevamente
                      </div>
                    @endif
                    @if(Request::segment(2) == 'error')
                      <div class="alert alert-danger" role="alert">
                        Los datos ingresados son incorrectos. Debe modificarlos e intentar nuevamente
                      </div>
                    @endif
                    <form class="user" method="POST" action="{{ url('enter') }}">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" aria-describedby="emailHelp" placeholder="RUT" id="rut" name="rut">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control form-control-user" id="exampleInputPassword" name="password" placeholder="Contraseña">
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            {{ __('Ingresar') }}
                        </button>
                        <br>
                        <center><a href="/rut">¿Olvidastes tu contraseña?</a></center>
                    </form>
                  <hr>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
@endsection
