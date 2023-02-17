<!DOCTYPE html>
<html lang="en" data-theme="{{ isset($gradiant) ? $gradiant : 'color-one' }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate 1</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">


    <!-- <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet"> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pinyon+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet"> -->

    {{-- <link rel="stylesheet" href="style.css"> --}}
   
    <style>

        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@500&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Pinyon+Script&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400;600;700;800&display=swap');

        :root {
            --cursive-font: 'Cinzel', serif;
            --sub-font: 'Oswald', sans-serif;
            --holder-font: 'Pinyon Script', cursive;
            --montserrat-font: 'Montserrat', sans-serif;
            --has-earned: 'Dancing Script', cursive;
            --hour-text: 'Orbitron', sans-serif;
            --course-name: 'Baloo Bhaijaan 2', cursive;
            --theme-color: #b10d0d;
            --theme-color-2: #bb9a50;
            --black-color: #000;
            --gray-color: #a7a7a7;
            --box-shadow-color-gray: #a2a2a2;
            --gradient-1: linear-gradient(to right, #4a0000, #5a0000, #b10d0d, #b10d0d, #a00808, #920606, #6b0606, #6f0606, #9e0b0b, #b10d0d, #b10d0d, #b10d0d);
            --gradient-1-left: linear-gradient(to left, #4a0000, #5a0000, #b10d0d, #b10d0d, #a00808, #920606, #6b0606, #6f0606, #9e0b0b, #b10d0d, #b10d0d, #b10d0d);
            --gradient-2: linear-gradient(to right, #b78628, #be8c26, #c59123, #cb971f, #d29d1b, #d7a118, #dba516, #e0a912, #e4ac10, #e7af0e, #ebb30c, #eeb609);
        }

        [data-theme="color-two"]  {
            --theme-color: #554360;
            --gradient-1: linear-gradient(to right, #2a243b, #2e273e, #312941, #352c45, #392f48, #3d324b, #40344f, #443752, #483a55, #4c3d59, #51405c, #554360);
            --gradient-1-left: linear-gradient(to left, #2a243b, #2e273e, #312941, #352c45, #392f48, #3d324b, #40344f, #443752, #483a55, #4c3d59, #51405c, #554360);
        }
        
        [data-theme="color-three"] {
            --theme-color: #2a475b;
            --gradient-1: linear-gradient(to right, #0f2a3e, #102c40, #112e43, #123045, #133248, #15344b, #18374d, #1a3950, #1e3c53, #224055, #264358, #2a475b);
            --gradient-1-left: linear-gradient(to left, #0f2a3e, #102c40, #112e43, #123045, #133248, #15344b, #18374d, #1a3950, #1e3c53, #224055, #264358, #2a475b);
        }

        [data-theme="color-four"]  {
            --theme-color: #6f0000;
            --gradient-1: linear-gradient(to right, #200122, #27001f, #2c001b, #310016, #35000f, #3b000c, #410008, #460003, #500004, #5b0003, #650002, #6f0000);
            --gradient-1-left: linear-gradient(to left, #200122, #27001f, #2c001b, #310016, #35000f, #3b000c, #410008, #460003, #500004, #5b0003, #650002, #6f0000);
        }

        [data-theme="color-five"]  {
            --theme-color: #1d7280;
            --gradient-1: linear-gradient(to right, #102e4c, #102e4c, #102e4c, #102e4c, #102e4c, #0f3351, #0e3957, #0c3e5c, #084b67, #095870, #116579, #1d7280);
            --gradient-1-left: linear-gradient(to left, #102e4c, #102e4c, #102e4c, #102e4c, #102e4c, #0f3351, #0e3957, #0c3e5c, #084b67, #095870, #116579, #1d7280);
        }

        [data-theme="color-six"]  {
            --theme-color: #365476;
            --gradient-1: linear-gradient(to right, #141e30, #19253a, #1d2d44, #22354e, #263d59, #29425f, #2b4665, #2e4b6b, #304d6e, #324f70, #345273, #365476);
            --gradient-1-left: linear-gradient(to left, #141e30, #19253a, #1d2d44, #22354e, #263d59, #29425f, #2b4665, #2e4b6b, #304d6e, #324f70, #345273, #365476);
        }

        [data-theme="color-seven"]  {
            --theme-color: #414345;
            --gradient-1:linear-gradient(to right, #040404, #0c0c0c, #121212, #171717, #1b1b1b, #202020, #242425, #29292a, #2f2f31, #353637, #3b3c3e, #414345);
            --gradient-1-left:linear-gradient(to left, #040404, #0c0c0c, #121212, #171717, #1b1b1b, #202020, #242425, #29292a, #2f2f31, #353637, #3b3c3e, #414345);
        }

        [data-theme="color-eight"]  {
            --theme-color: #237a57;
            --gradient-1: linear-gradient(to right, #093028, #09382d, #0a4032, #0d4836, #10503a, #13563e, #165d41, #196345, #1b6949, #1e6e4e, #207452, #237a57);
            --gradient-1-left: linear-gradient(to left, #093028, #09382d, #0a4032, #0d4836, #10503a, #13563e, #165d41, #196345, #1b6949, #1e6e4e, #207452, #237a57);
        }

        [data-theme="color-nine"]   {
            --theme-color: #734b6d;
            --gradient-1: linear-gradient(to right, #42275a, #42275a, #42275a, #42275a, #42275a, #462a5b, #4b2c5d, #4f2f5e, #593662, #623c65, #6b4469, #734b6d);
            --gradient-1-left: linear-gradient(to left, #42275a, #42275a, #42275a, #42275a, #42275a, #462a5b, #4b2c5d, #4f2f5e, #593662, #623c65, #6b4469, #734b6d);
        }

        [data-theme="color-ten"]  {
            --theme-color: #aa076b;
            --gradient-1: linear-gradient(to right, #61045f, #61045f, #61045f, #61045f, #61045f, #670460, #6d0362, #730363, #810266, #8f0368, #9c046a, #aa076b);
            --gradient-1-left: linear-gradient(to left, #61045f, #61045f, #61045f, #61045f, #61045f, #670460, #6d0362, #730363, #810266, #8f0368, #9c046a, #aa076b);
        }

        /* [color-elevan] */

        /* :root {
            --theme-color: #6441a5;
            --gradient-2: linear-gradient(to left top, #2a0845, #2a0845, #2a0845, #2a0845, #2a0845, #2f0d4c, #341154, #39165b, #44206d, #4f2b7f, #5a3692, #6441a5);
        } */

        /* [color-twelve] */

        /* :root {
            --theme-color: #532753;
            --gradient-2: linear-gradient(to left top, #310a31, #330d33, #361036, #381238, #3b153b, #3e173e, #411a41, #441c44, #481f48, #4b214b, #4f244f, #532753);
        } */


        .certificate_1_main {
            border: 2px solid var(--theme-color);
        }
        .certificate_1_bg {
            /* background-image: url(../certificate_1/img/bg-1.jpg); */
            background-image: url(.../certificate/img/bg-1.jpg);
            padding: 10px 10px;
            background-size: cover;
            background-repeat: no-repeat;
            position: relative;
            height: 700px;
        }
        .certificate_border {
            width: 80%;
            margin: 0 auto;
            text-align: center;
            padding: 50px 50px;
            height: 675px;
        }
        .certificate_heading h1 {
            font-family: var(--cursive-font);
            text-transform: capitalize;
            font-size: 35px;
            margin: 0;
        }
        .certificate_heading span {
            font-family: var(--sub-font);
            font-size: 30px;
            text-transform: uppercase;
        }
        .certificate_heading {
            border-bottom: 2px solid var(--black-color);
            display: inline-block;
        }

        .certificate_sub_heading {
            padding: 20px 0 0;
        }
        .certificate_sub_heading h3 {
            font-family: var(--has-earned);
            font-size: 30px;
            margin: 0;
        }
        .certificate_description {
            padding: 40px 0 0;
        }

        .certificate_sub_heading span {
            font-family: var(--montserrat-font);
            font-weight: 500;
            color: var(--gray-color);
            border-top: 2px dotted;
            padding-top: 10px;
            display: inline-block;
            margin-top: 10px;
        }
        .certificate_holder {
            padding: 40px 0 0;
        }
        .certificate_holder h2 {
            font-size: 50px;
            margin: 0;
            font-family: var(--holder-font);
            color: var(--theme-color);
        }
        .certificate_description p {
            font-size: 16px;
            font-family: var(--montserrat-font);
            font-weight: 500;
            color: var(--gray-color);
            height: 73px;
            overflow: hidden;
            margin: 0;
        }
        .certificate_sidebar {
            height: 80%;
            width: 70px;
            display: inline-block;
            position: absolute;
            overflow: hidden;
            margin: 0 auto;
            top: 10%;
            box-shadow: 0px 0px 20px var(--box-shadow-color-gray);
        }
        .certificate_sidebar_left {
            background-image: var(--gradient-1);
            left: 0;
            right: auto;
            border-radius: 0 100px 100px 0;
            background-image: var(--gradient-2);
        }
        .certificate_sidebar_right {
            background-image: var(--gradient-1);
            right: 0;
            left: auto;
            border-radius: 100px 0 0px 100px;
            background-image: var(--gradient-2);
        }
        .certificate_description h4 {
            padding: 30px 0 0;
            height: 60px;
            overflow: hidden;
            margin: 0;
            font-family: var(--course-name);
            color: var(--theme-color);
        }

        .top_1 {
            height: 95%;
            width: 71px;
            display: inline-block;
            position: absolute;
            overflow: hidden;
            margin: -10px 0px;
            border-radius: 0 100px 100px 0;
            top: 23px;
            box-shadow: 0px 0px 20px var(--box-shadow-color-gray);
            background-image: var(--gradient-1);
        }

        .top_2 {
            height: 95%;
            width: 71px;
            display: inline-block;
            position: absolute;
            overflow: hidden;
            margin: -10px 0px;
            border-radius: 100px 0 0px 100px;
            top: 23px;
            box-shadow: 0px 0px 20px var(--box-shadow-color-gray);
            background-image: var(--gradient-1-left);
        }

        .signature_section {
            text-align: right;
            display: inline-block;
        }
        .signature_text h4 {
            font-size: 20px;
            font-family: var(--cursive-font);
            font-weight: 500;
            color: var(--black-color);
            margin: 0;
            text-transform: capitalize;
        }
        .signature_image {
            height: 50px;
            width: 100%;
        }
        .signature_text {
            border-top: 2px solid var(--black-color);
            padding: 14px 30px 0;
        }
        .certificate_footer {
            padding: 30px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .certificate_logo {
            width: 150px;
            height: 90px;
        }
        .certificate_logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
    </style>
</head>

<body>

    <div class="certificate_1" id="boxes">
        <br>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="certificate_1_bg">
                        <div class="certificate_sidebar certificate_sidebar_left">
                            <div class="top_1">
                                <span></span>
                            </div>
                        </div>
                        <div class="certificate_sidebar certificate_sidebar_right">
                            <div class="top_2">
                                <span></span>
                            </div>
                        </div>
                        <div class="certificate_1_main">
                            <div class="certificate_border">
                                <div class="certificate_heading">
                                    <h1>{{ isset($settings->header_name)?$settings->header_name:'{store_name}'}} </h1>
                                    <!-- <span>Of achievement</span>   Harvard University -->
                                </div> 
                                <div class="certificate_holder">
                                    <h2>{{ isset($student->name)?$student->name:'{student_name}'}}</h2>
                                </div>
                                <div class="certificate_sub_heading">                       
                                    <h3>{{ __('has earned') }}</h3>
                                    <span>
                                        @if(empty($student->course_time))
                                            <span> {{ '<Course Time>' }} </span>
                                        @else 
                                            {{ $student->course_time }} {{ __('Hours') }}
                                        @endif
                                    </span>
                                </div>
                                <div></div>
                                <div class="certificate_description">
                                    <p>{{ __('A certificate of appreciation for students in word is a certificate that is written and sent via mail or any other online platform. The certificate is not printed, and all details are written virtually when coming up with the certificate. This process is fast and less costly.') }}</p>
                                    @if(empty($student->course_name))
                                        <span> {{ '<Course Name>' }} </span>
                                    @else 
                                        {{ $student->course_name }}
                                    @endif
                                    {{-- <h4>BPS PGS Initial PLO for Principals at Cluster Meetings</h4> --}}
                                </div>
                                <div class="certificate_footer">
                                    <div class="certificate_logo">
                                        <img src="{{asset('certificate/img/logo.png')}}" alt="logo">
                                    </div>
                                    <div class="signature_section">
                                        <div class="signature_image">
                                        </div>
                                        <div class="signature_text">
                                            <h4>{{ __('signature') }}</h4>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!isset($preview))
        <script src="{{ asset('./js/jquery-1.11.1.min.js') }} "></script>
        <script type="text/javascript" src="{{ asset('./js/html2pdf.bundle.min.js') }}"></script>
        <script>
            function closeScript() {
                setTimeout(function () {
                    window.open(window.location, '_self').close();
                }, 1000);
            }
        
            $(window).on('load', function () {
                var element = document.getElementById('boxes');
                var opt = {
                    filename: 'course_certificate',
                    image: {type: 'jpeg', quality: 1},
                    html2canvas: {scale: 1, dpi: 72, letterRendering: true},
                    jsPDF: {unit: 'in', format: 'A4', orientation:'landscape'}
                };
                html2pdf().set(opt).from(element).save().then(closeScript);
            });        
        </script>
    @endif

</body>

</html>