<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instagram2.0</title>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

</head>
<body>
<img src="https://medialeaks.ru/wp-content/uploads/2019/09/1-194-600x440.jpg">
<div id='vueapp'>

   <div class="comment" v-for='(com,index) in coms'>
     <span> автор: {{ com.name }} {{com.surname}} Почта:
     {{ com.email }} Время:  {{ com.time }}
     картиночка: {{com.image}}</span>
     <input type='button' value='Delete' @click='deleteRecord(com.id)'></td>
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
      </br>
      <label>Job</label>
      <input type="file" name="image" v-model="image">
      </br>
      <input type="button" @click="createCom()" value="Add">
    </form>

</div>
<script>
var app = new Vue({
  el: '#vueapp',
  data: {
      name: '',
      email: '',
      surname: '',
      comment: '',
      time:'',
      image: '',
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
    createCom: function(){
        console.log("Create contact!")
        let formData = new FormData();
        var d= new Date();
        kek=(d.getFullYear()+"-"+d.getMonth()+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds())
        console.log("name:", this.name)
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
            app.resetForm();
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
        this.image = '';
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
