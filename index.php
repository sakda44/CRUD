<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="fa/css/font-awesome.min.css">

</head>
<body>
    <div class="container">
       
        <div>
        <table class="table">
            <thead>
                <tr>
          
                    <td colspan=2 >
                    <input type="hidden" name="" id="txtRID" value="0"/>
                    <input type="password" class="form-control" id="txtUnique" placeholder="Unique Name" />
                
                    </td>
                    <td><input type="text" class="form-control" id="txtName" placeholder="Name" /></td>
                    <td><input type="text" class="form-control" id="txtOwner" placeholder="Owner" /></td>
                    <td><input type="text" class="form-control" id="txtWebSite" placeholder="WebSite"/></td>
                    <td><button class="btn btn-primary"  id="btnAdd"> <i class="fa fa-plus-circle" aria-hidden="true"></i> Add </button></td>

                </tr>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Owner</th>
                    <th>Website</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="tblRestaurants"></tbody>
       </table>
        </div>
      

        <select id="optCate" class="form-control"> 
            <option value="1"> Hello </option>
        </select>
    </div>





</body>
<script>
    function loadCate(){
        var url = "https://cjundang.me/api/category/gets";
        $.getJSON(url)
            .done((data)=>{
                var line = "";
                $.each(data, (k, v)=>{
                    line += "<option value='" + v.cid + "'> "+ v.categories + "</option>";
                });
                $("#optCate").empty();
                $("#optCate").append(line);

            })

    }
    function loadRestaurants(){
        var url = "https://cjundang.me/api/restaurants/gets";
        $.getJSON(url).done((data)=>{
            var line = "";
            console.log(data); 
            $.each(data, (key, item)=>{
                    line += "<tr>";
                    line += "<td> <a class='btn btn-danger btn-sm' href='#' onClick='deleRestaurant("+ item.rid +")'><i class='fa fa-trash' aria-hidden='true'></i> dele </a> ";
                    line += "<a class='btn btn-primary  btn-sm' href='#' onClick='loadARestaurant("+ item.rid +")' > <i class='fa fa-pencil-square-o' aria-hidden='true'></i> edit </a></td>";
                    line += "<td>" + item.rid   + "</td>";
                    line += "<td>" + item.name   + "</td>";
                    line += "<td>" + item.owner_name   + "</td>";
                    line += "<td>  <a href='"+ item.website+"'> Link </a></td>";
                    line += "<td>";
                    line += "Opened </td>";
                    line += "</tr>";
                    console.log(line);

                
            });
            $("#tblRestaurants").empty();
            $("#tblRestaurants").append(line);
            
        })
        .fail((xhr, status, error)=>{
            
        });
    }

    function loadARestaurant(id){
        var url = "https://cjundang.me/api/restaurants/get/" + id;
        $.getJSON(url).done((data)=>{
            console.log(data);
            var item = data[0];
            $("#txtRID").val(item.rid);
            $("#txtName").val(item.name);
            $("#txtOwner").val(item.owner_name);
            $("#txtWebSite").val(item.website);

            
        })
        .fail((xhr, status, error)=>{
            
        });
    }

    function deleRestaurant(id){

        var unique_name = prompt("confirm to delete with Uniqe Name");
        if (unique_name != null){
            var data = new Object();
            data.unique_name = unique_name;
            data.rstatus = 1;
            var url = "https://cjundang.me/api/restaurants/status";
            $.post(
                url,
                { "data" : JSON.stringify(data)},
                (data, status)=>{
                    console.log(data + " " + status);
                    loadRestaurants();
                } 
            )
        }

         
    }

    function addRestaurant(){
        var data = new Object();
        data.unique_name = $("#txtUnique").val();
        data.name = $("#txtName").val();
        data.owner_name = $("#txtOwner").val();
        data.website = $("#txtWebSite").val();
        console.log(JSON.stringify(data));
        var rid = parseInt($("#txtRID").val());

        if( rid == 0){  // add
            var url = "https://cjundang.me/api/restaurants/add";
            $.post(
                url,
                { 
                    "data" : JSON.stringify(data)
                },
                (data, status)=>{
                    console.log(data + " " + status);
                    loadRestaurants();
                } 
            );
        }else{
            var url = "https://cjundang.me/api/restaurants/update";
            $.post(
                url,
                { 
                    "data" : JSON.stringify(data)
                },
                (data, status)=>{
                    console.log(data + " " + status);
                    loadRestaurants();
                } 
            );
        }
    }

    $(()=>{
         loadRestaurants();
         loadCate();
       $("#btnAdd").click(addRestaurant);
    })
</script>
</html> 
