<center>
    <h1 style="text-align:center;display:block;">Vand test round</h1>
</center>
<div>
  - Required
  <pre>
      + Php 7.2
  </pre>
</div>
<div>
    - Step up
    <pre>
        + composer install
        + php artisan config:clear
        + php artisan config:cache
        + php artisan db:seed --class="UserSeeder" (create use data for test with infor 0939432055/123456, will help you log in)
    </pre>
</div>
<div>
   - Link postman
   https://api.postman.com/collections/16766312-28cd91f9-4735-4894-8910-faffbc4a8188?access_key=PMAT-01H9B75B22HHV83ET1WGE7C1AW
</div>
<div>
    - docker (if you have use then you need to edit .env file)
  <pre>
        APP_URL=http://localhost
        DB_CONNECTION=mysql
        DB_HOST=mysql
        DB_PORT=3306
        DB_USERNAME=root
        DB_DATABASE=laravel #(you can change, if you want)
        DB_PASSWORD=password
  </pre>
    
</div>
