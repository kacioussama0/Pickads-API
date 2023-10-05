<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap');
        .container {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%,-50%);
        }
        body {
            background: #1a202c;
            color: white;
            text-align: center;
            font-family: 'Open Sans', sans-serif;
        }

        a {
            color: #fff;
        }
</style>

   <div class="container">
       <h1>PickADS</h1>
       <h2>Hello</h2>
       <p>This button to reset Password</p>
       <a href="http://localhost:5173/reset-password/{{$token}}">Reset Password</a>
       <span>Thank you for choosing <a href="pickads.net">PickAds</a></span>
       <p>Copyright © PickADS 2023 All rights reserved.</p>


   </div>

