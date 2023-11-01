
<head>
    <style>
        .menus {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            width: 70%;
            margin: auto;
            gap: 3rem;
            position: relative;
            top: 30px;
        }

        .item {
            width: 200px;
            height: auto;
            background-color: #f0f0f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .item .price {
            font-size: 25px;
            font-weight: bold;
        }

        .item .title {
            font-size: 20px;

        }

        .item span {
            padding: 5px 30px;
            background-color: #335499;
            border-radius: 20px;
        }

        .item .buttons,
.item-button {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    margin-top: 0.5rem;
}

.item-button a {
    text-decoration: none;
    color: white;
}

.item-button .button {
    background-color: #4CAF50;
    border: none;
    color: white;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    cursor: pointer;
    padding: 10px 20px;
    border-radius: 5px;
}

.item-button .button:hover {
    background-color: #45a049;
}

          .search input {
          height: 40px;
          width: 400px;
          border-radius: 10px;
        }
        .search input::placeholder {
          font-style: italic;
          font-size: 25px;
          position: relative;
          top: 2px;
          left: 10px;
        }
          .search {
          position: relative;
          left: 500px;
        }

        .image-box {
    width: 150px; /* Set the width of the image */
    height: 150px; /* Set the height of the image */
    overflow: hidden; /* Hide any overflowing content within the box */
    margin-bottom: 10px; /* Add some margin to separate images */
}

.image-box img {
    width: 100%; /* Make sure the image fills the container */
    height: auto; /* Maintain aspect ratio */
}

    </style>
</head>
<body>
        <a href="<?php echo URLROOT?>/menus/submitMenuitem">Create a New Menu</a>
       
   
    <div class="editingPlace">
        <!-- the app and edit panel opens here -->
    </div>
    <div class="menus" id="parentDiv">
      
      

<?php
          
          
     
     foreach ($data['menu'] as $menuitem) {  
             
        echo '<div class="item">';
        echo '<div class="bottom">';
        echo '<div class="image-box">';
        
        

        echo '<img src="' . URLROOT . '/uploads/' . basename($menuitem->imagePath) . '" alt="Menu Item Image">';

        echo '</div>';
        echo '<p class="title">' . $menuitem->itemName . '</p>';
        echo '<p class="price">LKR' . $menuitem->price . '</p>';
        echo '<p class="Average Prepare Time">Min' . $menuitem->averageTime . '</p>';
        echo '<div class="buttons">';
                     if ($menuitem->hidden == 0) {
                        // If menu item is hidden, show "Show" button
                        echo '<span class="button item-button"><a href="' . URLROOT . '/menus/showMenuitem/' . $menuitem->itemID . '">Show</a></span>';
                    } else {
                        // If menu item is shown, show "Hide" button
                        echo '<span class="button item-button"><a href="' . URLROOT . '/menus/hideMenuitem/' . $menuitem->itemID . '">Hide</a></span>';
                    }


                     echo '<span class="button item-button"><a href="' . URLROOT . '/menus/editMenuitem/' . $menuitem->itemID . '">Edit</a></span>';



                     //echo '<span class="replaceContentBtn"onclick="loadFile(' . $key['name'], $key['price'], $key['images'] . ')"> Edit Item </span>';
                     echo '</div>';
                     echo '</div>';
                     echo '</div>';
     };



        
        ?>

       
    </div>
</body>
