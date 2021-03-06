﻿<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instagram2.0</title>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

</head>
<body>
<img src="https://medialeaks.ru/wp-content/uploads/2019/09/1-194-600x440.jpg">
<div id='vueapp'>

   <div class="comment" v-for='(com,index) in coms'>
     <span> автор: {{ com.name }} {{com.surname}} Почта:
     {{ com.email }} Время публикации:  {{ com.time }}</span>  </br>
     <span>Комментарий:{{com.comment}}</span> </br>
     <section>
     <img :src="'uploads/'+ com.image"> </section>
     <input type='button' value='Delete' @click='deleteRecord(com.id)'>
   </div>
 </br>

    <form>
      <label>Имя</label>
      <input type="text" name="name" v-model="name">
</br>
<label>Фамилия</label>
      <input type="text" name="surname" v-model="surname">
      </br>
      <label>Email</label>
      <input type="email" name="email" v-model="email">
      </br>
      
      <label>Комментарий</label>
      <input type="text" name="comment" v-model="comment">
      </br><label>Введите, пожалуйста. сумму цифр на картинке:</label>
        <span id="aspm"></span></br>
      <input type="text" name="kapcha" v-model="kapcha">
      
</form>
<form name="uploader" enctype="multipart/form-data" method="POST">
        Выберете картинку: <input name="userfile"type="file" id="your-files" />
        <input type="submit" name="submit" @click="createCom()" value="Add">
      </br>
    </form>
</div>
<script type="text/javascript" language="javascript" src="md5.js"></script>
<script language="javascript">
function randomNumber(m,n){
    m = parseInt(m);
    n = parseInt(n);
    return Math.floor( Math.random() * (n - m + 1) ) + m;
};

var aspmA = randomNumber(1,23); 
var aspmB = randomNumber(1,23); 
document.getElementById('aspm').innerHTML = aspmA + ' + ' + aspmB + ' = '; 
var app = new Vue({
  el: '#vueapp',
  data: {
      name: '',
      email: '',
      surname: '',
      comment: '',
      time:'',
      file: '',
      kapcha: '',
      coms: []
  },
  mounted: function () {
    console.log('Hello from Vue!')
    this.getCom()
  },

  methods: {
    getCom: function(){
        axios.get('api/comments.php')
        .then(function (response) {
            console.log(response.data);
            app.coms = response.data;

        })
        .catch(function (error) {
            console.log(error);
        });
    },
    img: function(){
    var form_data = new FormData();
    form_data.append('file', this.file);
    axios({
                url: 'image.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(php_script_response){
                }
     });
},
    createCom: function(){
        console.log("Create contact!")
        var sum=aspmA+aspmB;
        if (this.kapcha!=sum){
            alert("Ах тыж бот...");
            aspmA = randomNumber(1,23); 
            aspmB = randomNumber(1,23); 
            document.getElementById('aspm').innerHTML = aspmA + ' + ' + aspmB + ' = ';
            location.reload();
            app.resetForm();
            return;
        }
        let formData = new FormData();
        var d= new Date();
        kek=(d.getFullYear()+"-"+d.getMonth()+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds())
        var control = document.getElementById("your-files");
    var i = 0,
        files = control.files,
        len = files.length;
 
    for (; i < len; i++) {
        console.log("Filename: " + files[i].name);
        this.image=files[i].name;
        console.log("Type: " + files[i].type);
        console.log("Size: " + files[i].size + " bytes");
    }
        console.log("name:", this.image)
        formData.append('name', this.name)
        formData.append('surname', this.surname)
        formData.append('comment', this.comment)
        formData.append('email', this.email)
        formData.append( 'time',kek)
        formData.append('image', this.image)
        var comment = {};
        formData.forEach(function(value, key){
            comment[key] = value;
        });

        axios({
            method: 'post',
            url: 'api/comments.php',
            data: formData,
            config: { headers: {'Content-Type': 'multipart/form-data' }}
        })
        .then(function (response) {
            console.log(response)
            app.coms.push(comment)
            app.resetForm();app.img();
        })
        .catch(function (response) {
            console.log(response)
        });
    },
    resetForm: function(){
        this.name = '';
        this.email = '';
        this.surname = '';
        this.comment = '';
        this.time = '';
        this.userfile='';
        this.kapcha='';
    },
    deleteRecord: function(id){
        let formData = new FormData();
        formData.append( 'id',id)
        var comment = {};
        formData.forEach(function(value, key){
            comment[key] = value;
        });

        axios({
            method: 'post',
            url: 'api/delete.php',
            data: formData,
            config: { headers: {'Content-Type': 'multipart/form-data' }}
        })
        .then(function (response) {
            app.getCom();
        })
        .catch(function (response) {
            console.log(response)
        });
    },
  }
}
)    
</script>
<script type="text/javascript">
    $("form[name='uploader']").submit(function(e) {
        var formData = new FormData($(this)[0]);

        $.ajax({
            url: 'image.php',
            type: "POST",
            data: formData,
            async: false,
            success: function (msg) {
            },
            error: function(msg) {
                alert('Ошибка!');
            },
            cache: false,
            contentType: false,
            processData: false
        });
        e.preventDefault();
    });
    </script>
</body>
</html> 
<style>
    input {
  width: 100%;
  padding: 2px 5px;
  margin: 2px 0;
  border: 1px solid red;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=button]{
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 4px 7px;
  text-decoration: none;
  margin: 2px 1px;
  cursor: pointer;
}
th, td {
  padding: 1px;
  text-align: left;
  border-bottom: 1px solid #ddd;
  
}
tr:hover {background-color: #f5f5f5;}

</style>