<?php
    /*
    ===============================================
    === Items Page
    ===============================================
    */
    ob_start(); //- Output Buffering Start
    session_start();

    if (isset($_SESSION['Username'])) {

        $PageTitle = 'Items';
            
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {


			$stmt = $con->prepare("SELECT 
										item.*, 
										category.Name AS cat_name, 
										users.Username 
									FROM 
										item
									INNER JOIN 
										category 
									ON 
										category.ID = item.Cat_ID
									INNER JOIN 
										users 
									ON 
										users.UserID = item.Member_ID
									ORDER BY 
										item_ID 
                                    DESC");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$items = $stmt->fetchAll();

			if (! empty($items)) {

			?>

			<h1 class="text-center">Manage Items</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Item Name</td>
							<td>Description</td>
							<td>Price</td>
							<td>Adding Date</td>
							<td>Category</td>
							<td>Username</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($items as $item) {
								echo "<tr>";
									echo "<td>" . $item['item_ID'] . "</td>";
									echo "<td>" . $item['Name'] . "</td>";
									echo "<td>" . $item['Description'] . "</td>";
									echo "<td>" . $item['Price'] . "</td>";
									echo "<td>" . $item['Add_Date'] ."</td>";
									echo "<td>" . $item['cat_name'] ."</td>";
									echo "<td>" . $item['Username'] ."</td>";
									echo "<td>
										<a href='items.php?do=Edit&itemid=" . $item['item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='items.php?do=Delete&itemid=" . $item['item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
										if ($item['Approve'] == 0) {
											echo "<a 
													href='items.php?do=Approve&itemid=" . $item['item_ID'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Approve</a>";
										}
									echo "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
				<a href="items.php?do=Add" class="btn btn-sm btn-primary">
					<i class="fa fa-plus"></i> New Item
				</a>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">There\'s No Items To Show</div>';
					echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary">
							<i class="fa fa-plus"></i> New Item
						</a>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($do == 'Add') { ?>

            <h1 class="text-center">Add New Items</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="POST">
					<!-- Start Name Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6">
							<input 
                                type="text" 
                                name="name" 
                                class="form-control" 
                                required="required" 
                                placeholder="Name Of The Items" />
						</div>
					</div>
					<!-- End Name Field -->
                    <!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10 col-md-6">
							<input 
                                type="text" 
                                name="description" 
                                class="form-control" 
                                required="required" 
                                placeholder="Description Of The Items" />
						</div>
					</div>
					<!-- End Description Field -->
                    <!-- Start Price Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Price</label>
						<div class="col-sm-10 col-md-6">
							<input 
                                type="text" 
                                name="price" 
                                class="form-control" 
                                required="required" 
                                placeholder="Price Of The Items" />
						</div>
					</div>
					<!-- End Price Field -->
                    <!-- Start Country Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Country</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="country" 
								class="form-control" 
								required="required" 
								placeholder="Country of Made" />
						</div>
					</div>
					<!-- End Country Field -->
					<!-- Start Status Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-10 col-md-6">
							<select name="status">
								<option value="0">...</option>
								<option value="1">New</option>
								<option value="2">Like New</option>
								<option value="3">Used</option>
								<option value="4">Very Old</option>
							</select>
						</div>
					</div>
					<!-- End Status Field -->
					<!-- Start Members Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Member</label>
						<div class="col-sm-10 col-md-6">
							<select name="member">
								<option value="0">...</option>
								<?php
									$allMembers = getAllFrom("*", "users", "", "", "UserID");
									foreach ($allMembers as $user) {
										echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
									}
								?>
							</select>
						</div>
					</div>
					<!-- End Members Field -->
					<!-- Start Categories Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Category</label>
						<div class="col-sm-10 col-md-6">
							<select name="category">
								<option value="0">...</option>
								<?php
									$allCats = getAllFrom("*", "category", "", "", "ID");
									foreach ($allCats as $cat) {
										echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
									}
								?>
							</select>
						</div>
					</div>
					<!-- End Categories Field -->
					<!-- Start Tags Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Tags</label>
						<div class="col-sm-10 col-md-6">
							<input 
								type="text" 
								name="tags" 
								class="form-control" 
								placeholder="Separate Tags With Comma (,)" />
						</div>
					</div>
					<!-- End Tags Field -->
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Add Items" class="btn btn-primary btn-lg" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
			</div>


            <?php
    
        } elseif ($do == 'Insert') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Insert Items</h1>";
                echo "<div class='container'>";
                
                // Get Variables From The For
                $name		= $_POST['name'];
				$desc 		= $_POST['description'];
				$price 		= $_POST['price'];
				$country 	= $_POST['country'];
				$status 	= $_POST['status'];
				$member 	= $_POST['member'];
				$cat 		= $_POST['category'];
				$tags 		= $_POST['tags'];


                //- Validate The Form
                $formErrors = array();

                if (empty($name)) {
					$formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
				}
                if (empty($desc)) {
					$formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
				}

				if (empty($price)) {
					$formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
				}

				if (empty($country)) {
					$formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
				}

				if ($status == 0) {
					$formErrors[] = 'You Must Choose the <strong>Status</strong>';
				}
				if ($member == 0) {
					$formErrors[] = 'You Must Choose the <strong>Member</strong>';
				}

				if ($cat == 0) {
					$formErrors[] = 'You Must Choose the <strong>Category</strong>';
				}
                //- Loop into Error Array and Echo It
                foreach($formErrors as $error) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>' . '<br/>';
                }

                //- chek if theres no error proceed the Insertoperation
                if (empty($formErrors)) {

						// Insert Userinfo in Databases
						
                        $stmt = $con->prepare("INSERT INTO 
                                                        item(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags)

														VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");
                        $stmt->execute(array(

                            'zname' 	=> $name,
							'zdesc' 	=> $desc,
							'zprice' 	=> $price,
							'zcountry' 	=> $country,
							'zstatus' 	=> $status,
							'zcat'		=> $cat,
							'zmember'	=> $member,
							'ztags'		=> $tags

                            ));

                            //- Echo Success Message 
                            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Insert</div>';
                            redirectHome($theMsg);
                }

            } else {
                $theMsg = '<div class="alert alert-danger">you cant browse this page</div>';
                redirectHome($theMsg, 'back');
            }
    
        } elseif ($do == 'Edit') {  //- Edit page

            // Check If Get Request item Is Numeric & Get Its Integer Value
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM item WHERE item_ID = ?");
            $stmt->execute(array($itemid));
            
            $item = $stmt->fetch();
            
			$count = $stmt->rowCount();

            if ($count > 0) {?>
        
                <h1 class="text-center">Edit Items</h1>
                    <div class="container">
						<form class="form-horizontal" action="?do=Update" method="POST">
							<input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
							<!-- Start Name Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Name</label>
								<div class="col-sm-10 col-md-6">
									<input 
										type="text" 
										name="name" 
										class="form-control" 
										required="required" 
										placeholder="Name Of The Items" 
										value="<?php echo $item['Name'] ?>"/>
								</div>
							</div>
							<!-- End Name Field -->
							<!-- Start Description Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Description</label>
								<div class="col-sm-10 col-md-6">
									<input 
										type="text" 
										name="description" 
										class="form-control" 
										required="required" 
										placeholder="Description Of The Items" 
										value="<?php echo $item['Description'] ?>" />
								</div>
							</div>
							<!-- End Description Field -->
							<!-- Start Price Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Price</label>
								<div class="col-sm-10 col-md-6">
									<input 
										type="text" 
										name="price" 
										class="form-control" 
										required="required" 
										placeholder="Price Of The Items" 
										value="<?php echo $item['Price'] ?>"/>
								</div>
							</div>
							<!-- End Price Field -->
							<!-- Start Country Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Country</label>
								<div class="col-sm-10 col-md-6">
									<input 
										type="text" 
										name="country" 
										class="form-control" 
										required="required" 
										placeholder="Country of Made" 
										value="<?php echo $item['Country_Made'] ?>"/>
								</div>
							</div>
							<!-- End Country Field -->
							<!-- Start Status Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Status</label>
								<div class="col-sm-10 col-md-6">
									<select name="status">
									<option value="1" <?php if ($item['Status'] == 1) { echo 'selected'; } ?>>New</option>
									<option value="2" <?php if ($item['Status'] == 2) { echo 'selected'; } ?>>Like New</option>
									<option value="3" <?php if ($item['Status'] == 3) { echo 'selected'; } ?>>Used</option>
									<option value="4" <?php if ($item['Status'] == 4) { echo 'selected'; } ?>>Very Old</option>
								</select>
								</div>
							</div>
							<!-- End Status Field -->
							<!-- Start Members Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Member</label>
								<div class="col-sm-10 col-md-6">
									<select name="member">
										<option value="0">...</option>
										<?php
											$allMembers = getAllFrom("*", "users", "", "", "UserID");
											foreach ($allMembers as $user) {
												echo "<option value='" . $user['UserID'] . "'"; 
												if ($item['Member_ID'] == $user['UserID']) { echo 'selected'; } 
												echo">" . $user['Username'] . "</option>";
											}
										?>
									</select>
								</div>
							</div>
							<!-- End Members Field -->
							<!-- Start Categories Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Category</label>
								<div class="col-sm-10 col-md-6">
									<select name="category">
										<option value="0">...</option>
										<?php
											$allCats = getAllFrom("*", "category", "", "", "ID");
											foreach ($allCats as $cat) {
												echo "<option value='" . $cat['ID'] . "'";
												if ($item['Cat_ID'] == $cat['ID']) { echo 'selected'; }
												echo">" . $cat['Name'] . "</option>";
											}
										?>
									</select>
								</div>
							</div>
							<!-- End Categories Field -->
							<!-- Start Tags Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Tags</label>
								<div class="col-sm-10 col-md-6">
									<input 
										type="text" 
										name="tags" 
										class="form-control" 
										placeholder="Separate Tags With Comma (,)" 
										value="<?php echo $item['tags'] ?>" />
								</div>
							</div>
							<!-- End Tags Field -->
							<!-- Start Submit Field -->
							<div class="form-group form-group-lg">
								<div class="col-sm-offset-2 col-sm-10">
									<input type="submit" value="Save Items" class="btn btn-primary btn-lg" />
								</div>
							</div>
							<!-- End Submit Field -->
						</form>
							<?php

							// Select All Users Except Admin 
		
							$stmt = $con->prepare("SELECT 
														comments.*, users.Username AS Member  
													FROM 
														comments
													INNER JOIN 
														users 
													ON 
														users.UserID = comments.user_id
													WHERE item_id = ?");
		
							// Execute The Statement
		
							$stmt->execute(array($itemid));
		
							// Assign To Variable 
		
							$rows = $stmt->fetchAll();
		
							if (! empty($rows)) {
								
							?>
							<h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
							<div class="table-responsive">
								<table class="main-table text-center table table-bordered">
									<tr>
										<td>Comment</td>
										<td>User Name</td>
										<td>Added Date</td>
										<td>Control</td>
									</tr>
									<?php
										foreach($rows as $row) {
											echo "<tr>";
												echo "<td>" . $row['comment'] . "</td>";
												echo "<td>" . $row['Member'] . "</td>";
												echo "<td>" . $row['comment_Date'] ."</td>";
												echo "<td>
													<a href='comment.php?do=Edit&comid=" . $row['c_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
													<a href='comment.php?do=Delete&comid=" . $row['c_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
													if ($row['Status'] == 0) {
														echo "<a href='comment.php?do=Approve&comid="
																 . $row['c_ID'] . "' 
																class='btn btn-info activate'>
																<i class='fa fa-check'></i> Approve</a>";
													}
												echo "</td>";
											echo "</tr>";
										}
									?>
									<tr>
								</table>
							</div>
							<?php } ?>
						</div>
		
					<?php
		
					// If There's No Such ID Show Error Message
		
					} else {
		
						echo "<div class='container'>";
		
						$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
		
						redirectHome($theMsg);
		
						echo "</div>";
		
					}			

    
        } elseif ($do == 'Update') {

			echo "<h1 class='text-center'>Update Item</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$id 		= $_POST['itemid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$price 		= $_POST['price'];
				$country	= $_POST['country'];
				$status 	= $_POST['status'];
				$cat 		= $_POST['category'];
				$member 	= $_POST['member'];
				$tags 		= $_POST['tags'];

				// Validate The Form

				$formErrors = array();

				if (empty($name)) {
					$formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
				}

				if (empty($desc)) {
					$formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
				}

				if (empty($price)) {
					$formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
				}

				if (empty($country)) {
					$formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
				}

				if ($status == 0) {
					$formErrors[] = 'You Must Choose the <strong>Status</strong>';
				}

				if ($member == 0) {
					$formErrors[] = 'You Must Choose the <strong>Member</strong>';
				}

				if ($cat == 0) {
					$formErrors[] = 'You Must Choose the <strong>Category</strong>';
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					// Update The Database With This Info

					$stmt = $con->prepare("UPDATE 
												item 
											SET 
												Name = ?, 
												Description = ?, 
												Price = ?, 
												Country_Made = ?,
												Status = ?,
												Cat_ID = ?,
												Member_ID = ?,
												tags = ?
											WHERE 
												item_ID = ?");

					$stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id));

					// Echo Success Message

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		}elseif ($do == 'Delete') {

			echo "<h1 class='text-center'>Delete Item</h1>";
			echo "<div class='container'>";

				// Check If Get Request Item ID Is Numeric & Get The Integer Value Of It

				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('item_ID', 'item', $itemid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM item WHERE item_ID = :zid");

					$stmt->bindParam(":zid", $itemid);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} elseif ($do == 'Approve') {

			echo "<h1 class='text-center'>Approve Item</h1>";
			echo "<div class='container'>";

				// Check If Get Request Item ID Is Numeric & Get The Integer Value Of It

				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('item_ID', 'item', $itemid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE 
												item 
											SET 
												Approve = 1 
											WHERE 
												item_ID = ?");

					$stmt->execute(array($itemid));

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		}

		include $tpl . 'footer.php';

	} else {
         header('Location: index.php');
        exit();
    }
    
    
    ob_end_flush(); //- Release The Output

?>