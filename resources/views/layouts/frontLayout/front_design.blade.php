<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Dentistry is dental treatment clinic website templates free download for
    dentists, dental doctor and hospital.">
    <meta name="keywords" content="">
    <title>Pearl White Dental Care</title>
    <!-- Bootstrap -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Style CSS -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link href="{{asset('css/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/emojionearea.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/owl.theme.default.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/fontello.css')}}">
    <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('js/jquery.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{asset('js/emojionearea.min.js')}}"></script>


</head>

<body>
        @include('layouts.frontLayout.header')
        @include('flash-message')
        @yield('content')

        @include('layouts.frontLayout.footer')
   <!-- Modal -->
   <div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalTitle">Write to Us</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{url('/appointment')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Your Name</label>
                            <input type="text" name = "name" class="form-control" required=""  placeholder="Kindly Write Your Name">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Email Address</label>
                            <input type="email" name = "email" class="form-control" required=""  placeholder="Email Address">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Reason for Appointment</label>
                            <textarea class="form-control" name="appointment" required="" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Date</label>
                            <input type="date" name="date" class="form-control" required=""  placeholder="Select a Date">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- footer close -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/navigation.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/menumaker.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{asset('js/jquery.sticky.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/sticky-header.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/owl.carousel.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/slider.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/testimonial-slider.js')}}"></script>
</body>

</html>
