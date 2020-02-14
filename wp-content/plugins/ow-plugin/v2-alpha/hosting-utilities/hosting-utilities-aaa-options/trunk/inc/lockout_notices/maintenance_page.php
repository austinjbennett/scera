<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Under Maintenance</title>
    <link href='https://fonts.googleapis.com/css?family=Inconsolata:400,700' rel='stylesheet' type='text/css'>


    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css'>
    <!--<link rel='stylesheet prefetch' href='https://wordpressoverwatch.com/fa-cogs/css/fontello.css'>-->

    <meta http-equiv="refresh" content="100"> <!-- Refresh the page every 100 seconds -->

    <style>
    * {
      margin: 0;
      padding: 0;
    }

    html, body {
      height: 100%;
      background-color: #606060;
      background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAYAAAAMCAYAAABBV8wuAAAAR0lEQVR42oXOsQkAQQhE0StKRQ20/6o8RjBZcDd40R/ELyIKVLUxc9uDuxfMgIjgEsysQERaZsIlnCdmuId5bwbvcJyYwR5+DGh4DQAjsYAAAAAASUVORK5CYII=');
      color: white;
      font-family: 'Inconsolata', monospace;
      font-size: 100%;
    }

    .maintenance {
      text-transform: uppercase;
      margin-bottom: 1rem;
      font-size: 3rem;
    }

    .container {
      display: table;
      margin: 0 auto;
      max-width: 1024px;
      width: 100%;
      height: 100%;
      -webkit-align-content: center;
          -ms-flex-line-pack: center;
              align-content: center;
      position: relative;
      box-sizing: border-box;
    }
    .container .what-is-up {
      position: absolute;
      width: 100%;
      top: 50%;
      -webkit-transform: translateY(-50%);
              transform: translateY(-50%);
      display: block;
      vertical-align: middle;
      text-align: center;
      box-sizing: border-box;
    }
    .container .what-is-up .spinny-cogs {
      display: block;
      margin-bottom: 2rem;
    }

    @-webkit-keyframes fa-spin-one {
      0% {
        -webkit-transform: translateY(-2rem) rotate(0deg);
        transform: translateY(-2rem) rotate(0deg);
      }
      100% {
        -webkit-transform: translateY(-2rem) rotate(-359deg);
        transform: translateY(-2rem) rotate-(359deg);
      }
    }
    @keyframes fa-spin-one {
      0% {
        -webkit-transform: translateY(-2rem) rotate(0deg);
        transform: translateY(-2rem) rotate(0deg);
      }
      100% {
        -webkit-transform: translateY(-2rem) rotate(-359deg);
        transform: translateY(-2rem) rotate-(359deg);
      }
    }
    .fa-spin-one, .container .what-is-up .spinny-cogs .fa:nth-of-type(1) {
      -webkit-animation: fa-spin-one 1s infinite linear;
      animation: fa-spin-one 1s infinite linear;
    }

    @-webkit-keyframes fa-spin-two {
      0% {
        -webkit-transform: translateY(-0.5rem) translateY(1rem) rotate(0deg);
        transform: translateY(-0.5rem) translateY(1rem) rotate(0deg);
      }
      100% {
        -webkit-transform: translateY(-0.5rem) translateY(1rem) rotate(-359deg);
        transform: translateY(-0.5rem) translateY(1rem) rotate(-359deg);
      }
    }
    @keyframes fa-spin-two {
      0% {
        -webkit-transform: translateY(-0.5rem) translateY(1rem) rotate(0deg);
        transform: translateY(-0.5rem) translateY(1rem) rotate(0deg);
      }
      100% {
        -webkit-transform: translateY(-0.5rem) translateY(1rem) rotate(-359deg);
        transform: translateY(-0.5rem) translateY(1rem) rotate(-359deg);
      }
    }
    .fa-spin-two, .container .what-is-up .spinny-cogs .fa:nth-of-type(3) {
      -webkit-animation: fa-spin-two 2s infinite linear;
      animation: fa-spin-two 2s infinite linear;
    }

    </style>




  </head>

  <body>

    <div class="container">
  <div class="what-is-up">

    <div class="spinny-cogs">
      <i class="fa fa-cog" aria-hidden="true"></i>
      <i class="fa fa-5x fa-cog fa-spin" aria-hidden="true"></i>
      <i class="fa fa-3x fa-cog" aria-hidden="true"></i>
    </div>
    <h1 class="maintenance">Under Maintenance</h1>
    <h2><?= get_option('hu_admin_settings', array())['maintenance_mode_message'] ?? ''; ?></h2>
  </div>
</div>





  </body>
</html>
