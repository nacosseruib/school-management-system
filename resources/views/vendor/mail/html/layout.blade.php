<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
        #boxshadow {
          position: relative;
          box-shadow: 1px 2px 4px rgba(0, 0, 0, .5);
          padding: 0px !important;
          margin: 0px !important;
          background: white;
          width: 100%!important;
            min-width: 100%!important;
            max-width: 1000px!important;
            height: auto!important;
            background-image:url({{url('appAssets/app/email-background-image-690x362.png')}});
           
        }
        
        #boxshadow img {
          width: 100%;
          border: 1px solid #8a4419;
          border-style: inset;
        }
        #boxshadow::after {
          content: '';
          position: absolute;
          z-index: -1; /* hide shadow behind image */
          box-shadow: 0 15px 20px rgba(0, 0, 0, 0.3);
          width: 70%;
          left: 15%; /* one half of the remaining 30% */
          height: 100px;
          bottom: 0;
          font-size:15px; 
        }
    </style>
    
    <div id="boxshadow" class="wrapper">
        <div><!--style="background-image: url('{{ asset('appAssets/app/email-background-image-690x362.png')}}');"-->
            <table width="100%" cellpadding="0" cellspacing="0"> <!--class="wrapper"-->
                <!--{{ $header ?? '' }}-->
        
                <!-- Email Body -->
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0">
                        <table class="inner-body" align="center" width="100%" cellpadding="0" cellspacing="0">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell">
                                    {{ Illuminate\Mail\Markdown::parse($slot) }}
        
                                    {{ $subcopy ?? '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                
                {{ $footer ?? '' }}
            </table>
        </div>
    </div>
</body>
</html>
