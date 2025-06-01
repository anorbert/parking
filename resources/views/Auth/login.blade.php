@extends('layouts.app')

@section('content')
<style>
  body.login {
    background: #f7f7f7;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .login-wrapper {
    background: white;
    padding: 40px 30px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
  }

  .input-lg {
    height: 50px;
    font-size: 18px;
    padding: 10px 15px;
  }

  .btn-lg {
    height: 50px;
    font-size: 18px;
    font-weight: 600;
  }

  .login-header {
    text-align: center;
    margin-bottom: 30px;
  }
</style>

<body class="login">
  <div class="login-wrapper">
    <h2 class="login-header">Login to Parking System</h2>
    <form method="POST" action="{{ route('user_login.store') }}">
      @csrf

      <div class="form-group mb-3">
        <input type="tel" name="phone" class="form-control input-lg" 
               placeholder="Phone (e.g. 0781234567)" required value="{{ old('phone') }}" />
        @error('phone')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>

      <div class="form-group mb-4">
        <input type="password" name="pin" class="form-control input-lg" 
               placeholder="4-digit PIN" required maxlength="4" minlength="4" />
        @error('pin')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary btn-lg w-100">Log In</button>

      <div class="text-center mt-3">@extends('layouts.app')

@section('content')
<body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form method="POST" action="{{ route('user_login.store') }}">
                        @csrf
                        <h1>Login</h1>

                        {{-- Phone Input --}}
                        <div>
                            <input 
                                type="tel" 
                                name="phone" 
                                class="form-control" 
                                placeholder="Phone (e.g. 078xxxxxxx)" 
                                required 
                                value="{{ old('phone') }}" 
                                style="height: 50px; font-size: 18px;"
                            />
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- PIN Input --}}
                        <div>
                            <input 
                                type="password" 
                                name="pin" 
                                class="form-control" 
                                placeholder="4-digit PIN" 
                                required 
                                maxlength="4" 
                                pattern="\d{4}" 
                                title="Enter exactly 4 digits"
                                style="height: 50px; font-size: 18px;" 
                            />
                            @error('pin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="form-check text-left" style="margin-top: 10px; margin-bottom: 10px;">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="remember" 
                                id="remember" 
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        {{-- Submit --}}
                        <div>
                            <button 
                                type="submit" 
                                class="btn btn-primary submit" 
                                style="height: 50px; font-size: 18px;"
                            >
                                Log in
                            </button>
                            <a class="reset_pass" href="#">Lost your password?</a>
                        </div>

                        <div class="clearfix"></div>

                        {{-- Footer --}}
                        <div class="separator">
                            <br />
                            <div>
                                <h1><i class="fa fa-university"></i> Parking System</h1>
                                <p>© {{ date('Y') }} All Rights Reserved. Privacy and Terms</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</body>
@endsection

        <a href="#" class="text-decoration-none">Lost your PIN?</a>
      </div>
    </form>

    <div class="text-center mt-4">
      <p>New to site? <a href="#" class="text-decoration-none">Create Account</a></p>
      <p class="text-muted">&copy; {{ date('Y') }} Parking System. All Rights Reserved.</p>
    </div>
  </div>
</body>
@endsection
