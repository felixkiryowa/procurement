<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>ESP</title>
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
  </head>
  <body>

    <div class="container-scroller">
      @include('inc.navbar', ['title' => config('app.name') ])
        <div class="container-fluid page-body-wrapper full-page-wrapper">
          <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
              <div class="col-lg-4 mx-auto">
                <div class="auth-form-light text-left py-5 px-4 px-sm-5 login-top-margin">
                  <div class="brand-logo">
                    <img  src="{{ URL:: asset('img/logo.svg') }}" alt="logo">
                  </div>
                  <h4>Hello! let's get started</h4>
                  <h6 class="font-weight-light">Sign in to continue.</h6>
                  <br>
                  @if(session()->has('auth_error'))
                  <div class="alert alert-danger">
                      <strong>{{ session()->get('auth_error')}}</strong>
                  </div>
                  @endif
                  <form method="POST" action="{{route('login.custom')}}">
                    @csrf
                    <div class="form-group">
                      <input type="email" name="email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Enter Email Address">
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Enter Password">
                    </div>
                    <div class="mt-3">
                      <a class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" href="../../index.html">SIGN IN</a>
                    </div>
                    <div class="my-2 d-flex justify-content-between align-items-center">
                      <div class="form-check">
                        <label class="form-check-label text-muted">
                          <input type="checkbox" class="form-check-input">
                          Keep me signed in
                        </label>
                      </div>
                      <a href="#" class="auth-link text-black">Forgot password?</a>
                    </div>
                    <div class="mb-2">
                      <button type="button" class="btn btn-block btn-facebook auth-form-btn">
                        <i class="ti-facebook mr-2"></i>Connect using facebook
                      </button>
                    </div>
                    <div class="text-center mt-4 font-weight-light">
                      Don't have an account? <a href="register.html" class="text-primary">Create</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
      </div>
















        {{-- <div class="back">
            <div class="div-center">
                <div class="content">
                    <div class="login-logo">
                        <a href="/"><div id="logo" ><img src="{{ URL:: asset('img/logo.png') }}" style="margin:0 auto; display: block;"></div></a>
                      </div>
                    <hr />
                    <br>
                    @if(session()->has('auth_error'))
                    <div class="alert alert-danger">
                        <strong>{{ session()->get('auth_error')}}</strong>
                    </div>
                    @endif
                    <form method="POST" action="{{route('login.custom')}}">
                        @csrf
                        <div class="form-group {{ $errors->has('email') ? 'has-error': ''}}">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" name="email"  placeholder="Enter Email Address" />
                            {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
                        </div>
                        <div class="form-group {{ $errors->has('password') ? 'has-error': ''}}">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" name="password"  placeholder="Enter Password">
                            {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
                        </div>
                        <div class="float-right">
                            <button type="submit" class="btn btn-success login-btn">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
  </body>
  <script src="{{ asset('js/js/vendor.bundle.base.js') }}" defer></script>
</html>
