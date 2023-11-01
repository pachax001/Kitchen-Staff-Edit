<?php
class Menus extends Controller{
    public $menuModel;
    
    public function __construct()
    {
        
        $this->menuModel = $this->model('Menu');
    }
    public function index(){
        $menuitem = $this->menuModel->getMenuitem();
        $data = [
            'menu' => $menuitem
        ];
        $this->view('KStaff/index', $data);
    }
    public function submitMenuitem(){
        $formSubmissionSuccess = false; 
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if (isset($_FILES['imagePath']) && $_FILES['imagePath']['error'] === UPLOAD_ERR_OK) {
                // Handle image upload and get the image path
                $imagePath = $this->handleImageUpload($_FILES['imagePath']);
                if ($imagePath === false) {
                    // Handle image upload error
                    // Redirect or show an error message
                    die('Error: Image upload failed.');
                }
            } else {
                // Handle no image uploaded or upload error
                die('Error: No image uploaded or upload error.');
            }
    
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'itemName' => isset($_POST['itemName']) ? trim($_POST['itemName']) : '',
                'price' => isset($_POST['price']) ? trim($_POST['price']) : '',
                'averageTime' => isset($_POST['averageTime']) ? trim($_POST['averageTime']) : '',
                'itemName_err' => '',
                'price_err' => '',
                'averageTime_err' => '',
            ];
    
            if (empty($data['itemName'])){
                $data['itemName_err'] = 'Please enter ID';
            } else {
                if ($this->menuModel->findMenuitemByName($data['itemName'])){
                    $data['itemName_err'] = 'Item Name is already taken';
                }
            }
    
            if (empty($data['price'])){
                $data['price_err'] = 'Please enter price';
            }
    
            if (empty($data['averageTime'])){
                $data['averageTime_err'] = 'Please enter average time';
            }
    
            if (empty($data['itemName_err']) && empty($data['price_err']) && empty($data['averageTime_err'])){
                $menuData = [
                    'itemName' => $data['itemName'],
                    'price' => $data['price'],
                    'averageTime' => $data['averageTime'],
                    'imagePath' => $imagePath // Assign the image path to the menuData array
                ];
    
                // Call the model function to insert menu item data along with the image path
                if ($this->menuModel->submitMenuitem($menuData)){
                    // Redirect to a success page or show a success message
                    $formSubmissionSuccess = true;
                    redirect('menus');
                } else {
                    // Handle database insertion error
                    // Redirect or show an error message
                    die('Menu item insertion failed');
                }
            } else {
                // Validation failed, show the form with errors
                $this->view('KStaff/createmenu', $data);
            }
        } else {
            // Initial load of the page, show the form without errors
            $data = [
                'itemName' => '',
                'price' => '',
                'averageTime' => '',
                'itemName_err' => '',
                'price_err' => '',
                'averageTime_err' => '',
            ];
            $this->view('KStaff/createmenu', $data);
        }
        
    }
    
    private function handleImageUpload($imageFile) {
        $targetDirectory = 'C:\\wamp64\\www\\DineEase-DEE\\public\\uploads\\';

        $targetFile = $targetDirectory . basename($imageFile['name']);
    
        // Check if the file is an image
        $check = getimagesize($imageFile['tmp_name']);
        if ($check === false) {
            die('Error: Uploaded file is not an image.');
        }
    
        // Upload the image file
        if (move_uploaded_file($imageFile['tmp_name'], $targetFile)) {
            return $targetFile; // Return the uploaded image path
        } else {
            die('Error: Failed to move uploaded file.');
        }
    }
    
public function editMenuitem($itemID){
    $menuItem = $this->menuModel->getMenuItemById($itemID);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_FILES['imagePath']) && $_FILES['imagePath']['error'] === UPLOAD_ERR_OK) {
            // Handle image upload and get the image path
            $imagePath = $this->handleImageUpload($_FILES['imagePath']);
            if ($imagePath === false) {
                // Handle image upload error
                // Redirect or show an error message
                die('Error: Image upload failed.');
            }
        } else {
            // If no new image is uploaded, use the existing image path from the database
            $imagePath = $menuItem->imagePath;
        }
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'itemID' => $itemID,
            'itemName' => trim($_POST['itemName']),
            'price' =>  trim($_POST['price']),
            'averageTime' => trim($_POST['averageTime']),
            'imagePath' => $imagePath,
            'itemName_err' => '',
            'price_err' => '',
            'averageTime_err' => '',
        ];
        
        if (empty($data['itemName'])){
            $data['itemName_err'] = 'Please enter name';
        } else {
            if ($this->menuModel->findMenuitemByName($data['itemName'])){
                $data['itemName_err'] = 'Name is already taken';
            }
        }
        if (empty($data['price'])){
            $data['price_err'] = 'Please enter price';
        }
        if (empty($data['averageTime'])){
            $data['averageTime_err'] = 'Please enter time';
        }
        
        if (empty($data['itemName_err']) && empty($data['price_err']) && empty($data['averageTime_err'])){
            $menuData = [
                'itemID' => $itemID,
                'itemName' => $data['itemName'],
                'price' => $data['price'],
                'averageTime' => $data['averageTime'],
                'imagePath' => $imagePath // Assign the image path to the menuData array
            ];
            if ($this->menuModel->editMenuitem($menuData)){
                // Handle success, e.g., redirect to another page
                // header('Location: ' . URLROOT . '/menus/submitMenu');
                redirect('menus');
                exit();
            } else {
                $this->view('KStaff/editmenu', $data);
                die('Something went wrong');
            }
        } else {
            $this->view('KStaff/editmenu', $data);
        }

    } else {
        // Populate form fields with data from the database
        $data = [
            'itemID' => $itemID,
            'itemName' => $menuItem->itemName,
            'price' => $menuItem->price,
            'averageTime' => $menuItem->averageTime,
            'imagePath' => $menuItem->imagePath, // Pass the image path to the view
            'itemName_err' => '',
            'price_err' => '',
            'averageTime_err' => '',
        ];

        // Pass the data to the view
        $this->view('KStaff/editmenu', $data);
    }
}



 public function deleteMenuitem($itemID){
    if ($this->menuModel->deleteMenuItem($itemID)) {
        redirect('menus');
 }   
    } 
    public function hideMenuitem($itemID){
        if ($this->menuModel->hideMenuitem($itemID)) {
            redirect('menus');
     }   
        } 
        public function showMenuitem($itemID){
            if ($this->menuModel->showMenuitem($itemID)) {
                redirect('menus');
         }   
            }
    
}
?>


