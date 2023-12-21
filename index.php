<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>

* {
    padding: 0;
    margin: 0;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}

body {
    margin: 0;
    overflow: hidden;
    background-color: rgb(13, 5, 20);
  }

header {
    background: rgb(37,11,101);
    background: linear-gradient(90deg, rgba(37,11,101,1) 0%, rgba(63,9,121,1) 35%, rgba(85,0,255,1) 100%); 
    padding: 10px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 5px inset #3f0979;
}

.logo {
    max-width: 100px;
    height: auto;
    margin-right: 10px;
    filter: saturate(250%);
    border: 2px double #3f0979;
    border-radius: 0.2em;
    padding: 5px;
    transition: 200ms all;
}

.logo:hover {
    border: 2px solid #3f0979;
}

.enlaces {
    display: flex;
}

.enlace {
    margin-left: 10px;
    background-color: #bdaae4;
    padding: 5px;
    color: black;
    text-decoration: none;
    text-decoration: underline;
    transition: 300ms all;
}

.enlace:hover {
    background-color: whitesmoke;
}

.repositorio a img {
    width: 90px;
    transition: 200ms all;
}

.repositorio a img:hover {
    transform: scale(1.1);
}

.stars {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    pointer-events: none;
  }

  .star {
    position: absolute;
    background: white;
    border-radius: 50%;
    width: 2px;
    height: 2px;
    animation: moveStar 5s linear infinite;
  }

  @keyframes moveStar {
    from {
      transform: translateY(0) rotate(0deg);
    }
    to {
      transform: translateY(-100vh) rotate(360deg);
    }
  }

  @keyframes twinkle {
    from {
      opacity: 1;
    }
    to {
      opacity: 0.2;
    }
  }

.container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 80vh;
    margin: 0;
}

.centrado {
    text-align: center;
    padding: 10px;
    border: 4px inset #ccc;
    background-color: #ccc;
}

.centrado a {
    text-decoration: none;
    color: black;
}

.heartbeat {
    display: inline-block;
    font-size: 36px;
    animation: heartbeat 2.5s infinite;
}

    @keyframes heartbeat {
      0% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.1);
      }
      100% {
        transform: scale(1);
      }
    }

#title {
    font-size: 35px;
}

#title b {
    font-size: 15px;
    text-transform: uppercase;
}

footer {
    background-color: #333;
    color: #fff;
    padding: 20px;
    text-align: center;
    opacity: 50%;
  }

  footer a {
    color: #fff;
    text-decoration: none;
  }

  footer a:hover {
    text-decoration: underline;
  }

  footer p a img {
    width: 40px;
  }

</style>
<body>
    <header>
        <div>
            <img src="assets/logo/logo-php.png" alt="logo-php" class="logo">
            <img src="assets/logo/logo-mysql.png" alt="logo-mysql" class="logo">
        </div>

        <nav class="repositorio">
            <a href="https://github.com/santiago-semb/mi-propio-orm" target="_blank"><img src="assets/logo/logo-github.png" alt="logo-github"></a>
        </nav>
    </header>

    <div class="stars">
   </div>

    <div class="container">
        <div class="centrado heartbeat">
            <a href="#"><h1 id="title">MANUAL<b> page</b></h1></a>
        </div>
    </div>


<script>
        document.addEventListener('DOMContentLoaded', function() {
    const starsContainer = document.querySelector('.stars');

    for (let i = 0; i < 500; i++) {
      const star = document.createElement('div');
      star.classList.add('star');
      star.style.top = `${Math.random() * 100}vh`;
      star.style.left = `${Math.random() * 100}vw`;
      star.style.animationDelay = `-${Math.random() * 5}s`; // Agrega un retraso aleatorio para un efecto más natural
      starsContainer.appendChild(star);
    }
  });
</script>
</body>
</html>