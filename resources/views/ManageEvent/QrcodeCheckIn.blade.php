<!DOCTYPE html>
<html>
<head>
  <title>
      Attendize QRCode Check In: {{ $event->title }}
  </title>

    {!! HTML::style('assets/stylesheet/application.css') !!}
    {!! HTML::style('assets/stylesheet/qrcode-check-in.css') !!}
    <!-- {!! HTML::script('vendor/jquery/jquery.js') !!} -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">

  @include('Shared/Layouts/ViewJavascript')

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->

  <script>
      $(function() {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-Token': "<?php echo csrf_token() ?>"
              }
          });
      });
  </script>
  {!! HTML::script('vendor/qrcode-scan/llqrcode.js') !!}
  {!! HTML::script('vendor/qrcode-scan/webqr.js') !!}

  <style type="text/css">
    
    @import url('https://fonts.googleapis.com/css?family=Dosis');

      :root {
        /* generic */
        --gutterSm: 0.4rem;
        --gutterMd: 0.8rem;
        --gutterLg: 1.6rem;
        --gutterXl: 2.4rem;
        --gutterXx: 7.2rem;
        --colorPrimary400: #7e57c2;
        --colorPrimary600: #5e35b1;
        --colorPrimary800: #4527a0;
        --fontFamily: "Dosis", sans-serif;
        --fontSizeSm: 1.2rem;
        --fontSizeMd: 1.6rem;
        --fontSizeLg: 2.1rem;
        --fontSizeXl: 2.8rem;
        --fontSizeXx: 3.6rem;
        --lineHeightSm: 1.1;
        --lineHeightMd: 1.8;
        --transitionDuration: 300ms;
        --transitionTF: cubic-bezier(0.645, 0.045, 0.355, 1);
        
        /* floated labels */
        --inputPaddingV: var(--gutterMd);
        --inputPaddingH: var(--gutterLg);
        --inputFontSize: var(--fontSizeLg);
        --inputLineHeight: var(--lineHeightMd);
        --labelScaleFactor: 0.8;
        --labelDefaultPosY: 50%;
        --labelTransformedPosY: calc(
          (var(--labelDefaultPosY)) - 
          (var(--inputPaddingV) * var(--labelScaleFactor)) - 
          (var(--inputFontSize) * var(--inputLineHeight))
        );
        --inputTransitionDuration: var(--transitionDuration);
        --inputTransitionTF: var(--transitionTF);
      }

      *,
      *::before,
      *::after {
        box-sizing: border-box;
      }

      html {
        font-size: 10px;
      }

      body {
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        width: 100vw;
        height: 100vh;
        color: #455A64;
        background-color: #7E57C2;
        font-family: var(--fontFamily);
        font-size: var(--fontSizeMd);
        line-height: var(--lineHeightMd);
      }

      .Wrapper {
        flex: 0 0 80%;
        max-width: 80%;
      }

      .Title {
        margin: 0 0 var(--gutterXx) 0;
        padding: 0;
        color: #fff;
        font-size: var(--fontSizeXx);
        font-weight: 400;
        line-height: var(--lineHeightSm);
        text-align: center;
        text-shadow: -0.1rem 0.1rem 0.2rem var(--colorPrimary800);
      }

      .Input {
        position: relative;
      }

      .Input-text {
        display: block;
        margin: 0;
        padding: var(--inputPaddingV) var(--inputPaddingH);
        color: inherit;
        width: 100%;
        font-family: inherit;
        font-size: var(--inputFontSize);
        font-weight: inherit;
        line-height: var(--inputLineHeight);
        border: none;
        border-radius: 0.4rem;
        transition: box-shadow var(--transitionDuration);
      }

      .Input-text:focus {
        outline: none;
        box-shadow: 0.2rem 0.8rem 1.6rem var(--colorPrimary600);
      }

      .Input-label {
        display: block;
        position: absolute;
        bottom: 50%;
        left: 1rem;
        color: #311B92;
        font-family: inherit;
        font-size: var(--inputFontSize);
        font-weight: inherit;
        line-height: var(--inputLineHeight);
        opacity: 0;
        transform: 
          translate3d(0, var(--labelDefaultPosY), 0)
          scale(1);
        transform-origin: 0 0;
        transition:
          opacity var(--inputTransitionDuration) var(--inputTransitionTF),
          transform var(--inputTransitionDuration) var(--inputTransitionTF),
          visibility 0ms var(--inputTransitionDuration) var(--inputTransitionTF),
          z-index 0ms var(--inputTransitionDuration) var(--inputTransitionTF);
      }

      .Input-text:placeholder-shown + .Input-label {
        visibility: hidden;
        z-index: -1;
      }

      .Input-text:not(:placeholder-shown) + .Input-label,
      .Input-text:focus:not(:placeholder-shown) + .Input-label {
        visibility: visible;
        z-index: 1;
        opacity: 1;
        transform:
          translate3d(0, var(--labelTransformedPosY), 0)
          scale(var(--labelScaleFactor));
        transition:
          transform var(--inputTransitionDuration),
          visibility 0ms,
          z-index 0ms;
      }


  </style>


</head>
<body>
  <div id="main">
    <header id="header">
      <h2 class="text-center"><img style="width: 40px;" class="logo" alt="Attendize" src="{{ asset('/assets/images/logo-dark.png') }}"/><br><span style="font-size: 0.7em;">Check In: <strong>{{ $event->title }}</strong></span></h2>
    </header>

    <hr>

   

    <div id="mainbody">
      <table class="tsel" border="0" width="100%">
        <tr>
         <td valign="top" align="center" width="50%">
          <table class="tsel" border="0">
            <tr>
              <td colspan="2" align="center">
                <div id="outdiv">
                </div>
              </td>
            </tr>
          </table>
         </td>
        </tr>
        <tr>
          <td colspan="3" align="center">
            <!-- <p id="help-text">Put the QR code in front of your Camera (Not too close)</p> -->
            <input type="text" name="ticket_code" placeholder="Type code here..." >
          </td>
        </tr>

        <div class="Wrapper">
          <h1 class="Title">CSS Only Floated Labels!</h1>
          <div class="Input">
            <input type="text" id="input" class="Input-text" placeholder="Enter your name!">
            <label for="input" class="Input-label">Name</label>
          </div>
        </div>

        <!-- <tr>
          <td colspan="3" align="center">
            <p style="position: relative; bottom: -2em;"><a onclick="event.preventDefault(); workingAway = false; load();" href="{{ Request::url() }}"><i class="fa fa-refresh"></i> Scan another ticket</a></p>
            <div id="result"></div>
          </td>
        </tr> -->


        <ul class="list-group">
          <li class="list-group-item">Cras justo odio</li>
          <li class="list-group-item">Dapibus ac facilisis in</li>
          <li class="list-group-item">Morbi leo risus</li>
          <li class="list-group-item">Porta ac consectetur ac</li>
          <li class="list-group-item">Vestibulum at eros</li>
        </ul>


      </table>
    </div>&nbsp;

    <footer id="footer">
      <br>
      <br>
      <h5 align="center" style="color: #6D717A;">Powered By <a href="https://www.Oflander.com/">Oflander</a> </h5>
    </footer>
  </div>

 <!--  <canvas id="qr-canvas" width="800" height="600"></canvas> -->
  <!-- <script type="text/javascript">load();</script> -->
 <!--  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->

  <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

   -->

  <script>
    $(document).ready(function () {
      $("input").on('keyup', function (e) {
          if (e.keyCode == 13) {
            
            $.post("{{route('postValidateQRCode', ['event_id'=>$event->id])}}",//$( "#transdetnew" ).serialize()
            {
              qrcode_token : $(this).val()
            }
            ,
            function(data,status){
              
               
            });

          }
      });

    });    
    
    

  </script>


</body>


</html>
