<?php
    /*
    ===============================================
    === Manage Members Page
    === You Can Add | Edit | Delete Members From Here
    ===============================================
    */
    ob_start(); //- Output Buffering Start
    session_start();
    if (isset($_SESSION['Username'])) {

        $PageTitle = 'Members';
        
        include 'init.php';

        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStautus = 0';
        }

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; //- IF Condition ? True : False

        //- Start Manage Page

        if ($do == 'Manage') { //- Manage page

         // Select All Users Except Admin 
         $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");

         // Execute The Statement
         $stmt->execute();

		// Assign To Variable 
		$rows = $stmt->fetchAll();
        
        
        ?>
            <h1 class="text-center">Manage Members</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table manage-members text-center table table-bordered">
                            <tr>
                                <td>#ID</td>
                                <td>Avatar</td>
                                <td>Username</td>
                                <td>Email</td>
                                <td>Full Name</td>
                                <td>Registered Date</td>
                                <td>Control</td>
                            </tr>
                            <?php
                                foreach($rows as $row) {
                                    echo "<tr>";
                                        echo "<td>" . $row['UserID'] . "</td>";
                                        echo "<td>";
									if (empty($row['avatar'])) {
										echo 'No Image';
									} else {
										echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
									}
									echo "</td>";
                                        echo "<td>" . $row['Username'] . "</td>";
                                        echo "<td>" . $row['Email'] . "</td>";
                                        echo "<td>" . $row['FullName'] . "</td>";
                                        echo "<td>" . $row['Date'] . "</td>";
                                        echo "<td>
                                            <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                            <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                            if ($row['RegStautus'] == 0) {
                                                 echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-info activate'><i class='fa fa-close'></i> Activate </a>";  
                                            }
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </table>    
                    </div> 
                     <a href="members.php?do=Add" class="btn btn-primary"> <i class="fa fa-plus"></i> New Member </a>       
                </div>
            

      <?php } elseif ($do == 'Add') {  //- Add Page ?>

            <h1 class="text-center">Add New Member</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
					<!-- Start Username Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Username</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login Into Shop" />
						</div>
					</div>
					<!-- End Username Field -->
					<!-- Start Password Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-6">
							<input type="password" name="password" class="password form-control" required="required" autocomplete="new-password" placeholder="Password Must Be Hard & Complex" />
							<i class="show-pass fa fa-eye fa-2x"></i>
						</div>
					</div>
					<!-- End Password Field -->
					<!-- Start Email Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-6">
							<input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid" />
						</div>
					</div>
					<!-- End Email Field -->
					<!-- Start Full Name Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Full Name</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Your Profile Page" />
						</div>
					</div>
					<!-- End Full Name Field -->
					<!-- Start Avatar Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">User Avatar</label>
						<div class="col-sm-10 col-md-6">
							<input type="file" name="avatar" class="form-control" required="required" />
						</div>
					</div>
					<!-- End Avatar Field -->
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
			</div>

        <?php 
        } elseif ($do == 'Insert') { //- Insert page

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Insert Member</h1>";
                echo "<div class='container'>";

                // Upload Variables

				$avatarName = $_FILES['avatar']['name'];
				$avatarSize = $_FILES['avatar']['size'];
				$avatarTmp	= $_FILES['avatar']['tmp_name'];
				$avatarType = $_FILES['avatar']['type'];

				// List Of Allowed File Typed To Upload

				$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

                // Get Avatar Extension
                $ddr = '.';

				$avatarExtension = strtolower(end(explode($ddr, $avatarName)));

				// Get Variables From The Form
                $user 	= $_POST['username'];
                $pass 	= $_POST['password'];
				$email 	= $_POST['email'];
                $name 	= $_POST['full'];

                $hashPass = sha1($_POST['password']);

                //- Validate The Form
                $formErrors = array();

                if (strlen($user) < 4) {
                    $formErrors[] = 'Username cant be less than 4 chracters';
                }
                if (strlen($user) > 20) {
                    $formErrors[] = 'Username cant be Morethan 20 chracters';
                }
                if (empty($user)) {
                    $formErrors[] = 'Username cant be Empty';
                }
                if (empty($pass)) {
                    $formErrors[] = 'Password cant be Empty';
                }
                if (empty($name)) {
                    $formErrors[] = 'Full name cant be Empty';
                }
                if (empty($email)) {
                    $formErrors[] = 'E-mail cant be Empty';
                }
                if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
					$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
				}

				if (empty($avatarName)) {
					$formErrors[] = 'Avatar Is <strong>Required</strong>';
				}

				if ($avatarSize > 4194304) {
					$formErrors[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
				}
                //- Loop into Error Array and Echo It
                foreach($formErrors as $error) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>' . '<br/>';
                }

                //- chek if theres no error proceed the Insertoperation
                if (empty($formErrors)) {

                    $avatar = rand(0, 1000000000) . '_' . $avatarName;

					move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);

					// Check If User Exist in Database

                    $check = checkItem("Username", "users", $user);

					if ($check == 1) {

						$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';

					} else {
                        // Insert Userinfo in Databases
                        $stmt = $con->prepare("INSERT INTO 
                                                        users(Username, Password, Email, FullName, RegStautus, Date, avatar)
                                                    VALUES(:huser, :hpass, :hmail, :hname, 1,  now(), :havatar)");
                        $stmt->execute(array(

                            'huser'     => $user,
                            'hpass'     => $hashPass,
                            'hmail'     => $email,
                            'hname'     => $name,
                            'havatar'	=> $avatar

                            ));

                            //- Echo Success Message 
                            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Insert</div>';
                            redirectHome($theMsg);
                        }
                    
                }

            } else {
                $theMsg = '<div class="alert alert-danger">you cant browse this page</div>';
                redirectHome($theMsg, 'back');
            }

        } elseif ($do == 'Edit') { //- Edit page

            // Check If Get Request userid Is Numeric & Get Its Integer Value
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
            $stmt->execute(array($userid));
            
            $row = $stmt->fetch();
            
			$count = $stmt->rowCount();

            if ($count > 0) {?>
        
                <h1 class="text-center">Edit Member</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?do=Update" method="POST">
                            <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                            <!-- Start Username Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" />
                                </div>
                            </div>
                            <!-- End Username Field -->
                            <!-- Start Password Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
                                    <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
                                </div>
                            </div>
                            <!-- End Password Field -->
                            <!-- Start Email Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
                                </div>
                            </div>
                            <!-- End Email Field -->
                            <!-- Start Full Name Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Full Name</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required" />
                                </div>
                            </div>
                            <!-- End Full Name Field -->
                            <!-- Start Submit Field -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                                </div>
                            </div>
                            <!-- End Submit Field -->
                        </form>
                    </div>        
    
        <?php
            // If There's No Such ID Show Error Message
            } else {
                $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
                redirectHome($theMsg);
            }
        } elseif ($do == 'Update') { //- Update Page
            echo '<h1 class="text-center">Update Member</h1>';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                // Get Variables From The Form
                $id 	= $_POST['userid'];
				$user 	= $_POST['username'];
				$email 	= $_POST['email'];
                $name 	= $_POST['full'];

                // Password Trick
                $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
                
                //- Validate The Form
                $formErrors = array();

                if (strlen($user) < 4) {
                    $formErrors[] = 'Username cant be less than 4 chracters';
                }
                if (strlen($user) > 20) {
                    $formErrors[] = 'Username cant be Morethan 4 chracters';
                }
                if (empty($user)) {
                    $formErrors[] = 'Username cant be Empty';
                }
                if (empty($name)) {
                    $formErrors[] = 'Full name cant be Empty';
                }
                if (empty($email)) {
                    $formErrors[] = 'E-mail cant be Empty';
                }
                //- Loop into Error Array and Echo It
                foreach($formErrors as $error) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>' . '<br/>';
                }

                //- chek if theres no error proceed the update operation
                if (empty($formErrors)) {
                    // Update The Database With This Info
                    $stmt = $con->prepare("UPDATE 
                                                users 
                                            SET 
                                                Username = ?, 
                                                Email = ?, 
                                                FullName = ?, 
                                                Password = ? 
                                            WHERE 
                                                UserID = ?");

                    $stmt->execute(array($user, $email, $name, $pass, $id));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                    redirectHome($theMsg, 'back');
                }

            } else {
                $theMsg = '<div class="alert alert-danger">you cant browse this page</div>';
                redirectHome($theMsg);
            }
        } elseif ($do == 'Delete') {

            // Delete Member page
            echo "<h1 class='text-center'>Delete Member</h1>";
            echo "<div class='container'>";

            // Check If Get Request userid Is Numeric & Get Its Integer Value
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            // Select All Data Depend On This ID
            $check = checkItem('userid', 'users', $userid);

            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM users WHERE UserID = :huser");

				$stmt->bindParam(":huser", $userid);

				$stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
                redirectHome($theMsg);

            } else {
                $theMsg = '<div class="alert alert-danger">This ID NO EXist</div>';
                redirectHome($theMsg);
            }
            
        } elseif ($do == 'Activate') {
            
            // Activate Member page
            echo "<h1 class='text-center'>Activatee Member</h1>";
            echo "<div class='container'>";

            // Check If Get Request userid Is Numeric & Get Its Integer Value
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            // Select All Data Depend On This ID
            $check = checkItem('userid', 'users', $userid);

            if ($check > 0) {

                $stmt = $con->prepare("UPDATE users SET RegStautus = 1 WHERE UserID = ?");
				$stmt->execute(array($userid));

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Activate</div>';
                redirectHome($theMsg);

            } else {
                $theMsg = '<div class="alert alert-danger">This ID NO EXist</div>';
                redirectHome($theMsg);
            }
            
        }

        include $tpl . 'footer.php';
    } else {
        header('Location: index.php');
        exit();
    }
    ob_end_flush(); //- Release The Output
?>