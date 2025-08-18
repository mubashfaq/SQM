<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="x-ua-compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="Sydney Quality Meats - 404 Error Page">
      <meta name="keyword" content="Sydney Quality Meats, 404, error page">
      <meta name="author" content=""/>
      
      <!-- Page Title -->
      <title>Sydney Quality Meats | 404 Error</title>
      
      <!-- Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
      
      <!-- Bootstrap Icons -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
      
      <!-- Favicon -->  
      <link rel="icon" href="{{url('/')}}/AdminAssets/assets/images/favicon.ico" type="image/x-icon">
      
      <style>
         body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
         }
         
         .error-container {
            max-width: 800px;
            text-align: center;
            padding: 2rem;
         }
         
         .error-code {
            font-size: 8rem;
            font-weight: 700;
            background: linear-gradient(45deg, #6a06ddb7, #6366F1);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0;
            line-height: 1;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.1);
         }
         
         .error-image {
            max-width: 350px;
            margin: 1rem auto;
            filter: drop-shadow(5px 5px 10px rgba(0,0,0,0.15));
            animation: float 4s ease-in-out infinite;
         }
         
         @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
         }
         
         .home-button {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
         }
         
         .home-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 14px rgba(0,0,0,0.15);
         }
         
         .message-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background-color: white;
         }
      </style>
   </head>
   <body>
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-md-10">
               <div class="card border-0 message-card">
                  <div class="card-body p-md-5 p-4">
                     <div class="row align-items-center">
                        <div class="col-lg-6">
                           <h1 class="error-code">404</h1>
                           <h2 class="fs-1 fw-bold text-dark mb-3">Page Not Found</h2>
                           <p class="lead text-secondary mb-4">The page you're looking for doesn't exist or has been moved.</p>
                           <a href="{{url('/')}}" class="btn btn-secondary btn-lg home-button px-4 py-2">
                              <i class="bi bi-house-door-fill me-2"></i>Back to Home
                           </a>
                        </div>
                        <div class="col-lg-6 mt-4 mt-lg-0">
                           <img src="{{asset('assets/images/404.png')}}" 
                                class="error-image img-fluid" 
                                alt="404 Error Illustration">
                        </div>
                     </div>
                  </div>
               </div>
               
               <div class="text-center mt-4 text-secondary">
                  <p class="mb-0">Copyright &copy; <?php echo date('Y'); ?> Sydney Quality Meats. All rights reserved.</p>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Bootstrap Bundle with Popper -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
   </body>
</html>