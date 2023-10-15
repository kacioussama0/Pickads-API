<style>
    @import url('https://fonts.googleapis.com/css2?family=Epilogue:wght@100;200;300;400;500;600;700;800;900&display=swap');
        .container {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%,-50%);
        }
        body {
            background: #001733;
            color: white;
            text-align: center;
            font-family: 'Epilogue', sans-serif !important;
        }

        a {
            color: #fff;
        }
</style>

   <div class="container">
       <img src="{{asset('imgs/logo.svg')}}" alt="flexads">
       <h1>Bienvenue sur PickADS</h1>
       <h2>Bonjour , {{$user['username']}}</h2>

       <span>Merci d'avoir choisi<a href="pickads.net">PickAds</a></span>
       <div>
           Problème de connexion ?
           <a href="https://pickads.net/forgot_password/">https://pickads.net/forgot_password/</a>
       </div>

       <p>Copyright © PickADS 2023 Tous droits réservés.</p>
   </div>

