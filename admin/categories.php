<?php
    /*
    ===============================================
    === Categories Page
    ===============================================
    */
    ob_start(); //- Output Buffering Start
    session_start();

    if (isset($_SESSION['Username'])) {

        $PageTitle = 'Categories';
            
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') {
			$sort = 'ASC';
			$sort_array =array('ASC', 'DESC');
			if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

				$sort = $_GET['sort'];

			}
			$stmt2 =$con->prepare("SELECT * FROM category ORDER BY Ordering $sort");
			$stmt2->execute();
			$cats = $stmt2->fetchAll(); ?>

			<h1 class="text-center">Manage Category</h1>
			<div class="container categories">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-edit"></i> Manage Categories
						<div class="option pull-right">
							<i class="fa fa-sort"></i> Ordering: [
							<a class="<?php if ($sort == 'ASC') { echo 'active'; } ?>" href="?sort=ASC">Asc</a> | 
							<a class="<?php if ($sort == 'DESC') { echo 'active'; } ?>" href="?sort=DESC">Desc</a> ]
						</div>
					</div>	
						<div class="panel-body">
							<?php
								foreach($cats as $cat) {
									echo "<div class='cat'>";
										echo "<div class='hidden-buttons'>";
										echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
										echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
									echo "</div>";
										echo "<h3>" . $cat['Name'] . '</h3>';
										echo "<p>"; if($cat['Description'] == '') { 
											echo 'This category has no description'; 
										}else { 
											echo $cat['Description']; 
										} 
										if($cat['Visibility'] == 1) { 
											echo '<span class="visibility cat-span">
													  <i class="fa fa-eye"></i> 
													  Hidden
													</span>'; 
										}
										if($cat['Allow_Comment'] == 1) {
											echo '<span class="commenting cat-span">
													<i class="fa fa-close"></i> 
													Comment Disabled
													</span>'; 
										}
										if($cat['Allow_Ads'] == 1) { 
											echo '<span class="advertises cat-span">
													<i class="fa fa-close"></i>
													 Ads Disabled
													 </span>';
										} 
									echo "</div>";
									echo "<hr>";

								}
							?>	
						</div>
					</div>		
				</div>
				<a class="add-category btn btn-primary" href="categories.php?do=Add">
				<i class="fa fa-plus"></i> Add New Category</a>
			</div>			


			<?php
    
        } elseif ($do == 'Add') { ?>

            <h1 class="text-center">Add New Category</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="POST">
					<!-- Start Name Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name Of The Category" />
						</div>
					</div>
					<!-- End Name Field -->
					<!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="description" class="form-control" placeholder="Describe The Category" />
						</div>
					</div>
					<!-- End Description Field -->
					<!-- Start Ordering Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Ordering</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" />
						</div>
					</div>
					<!-- End Ordering Field -->
					<!-- Start Visibility Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Visible</label>
						<div class="col-sm-10 col-md-6">
							<div>
								<input id="vis-yes" type="radio" name="visibility" value="0" checked />
								<label for="vis-yes">Yes</label> 
							</div>
							<div>
								<input id="vis-no" type="radio" name="visibility" value="1" />
								<label for="vis-no">No</label> 
							</div>
						</div>
					</div>
					<!-- End Visibility Field -->
					<!-- Start Commenting Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Allow Commenting</label>
						<div class="col-sm-10 col-md-6">
							<div>
								<input id="com-yes" type="radio" name="commenting" value="0" checked />
								<label for="com-yes">Yes</label> 
							</div>
							<div>
								<input id="com-no" type="radio" name="commenting" value="1" />
								<label for="com-no">No</label> 
							</div>
						</div>
					</div>
					<!-- End Commenting Field -->
					<!-- Start Ads Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Allow Ads</label>
						<div class="col-sm-10 col-md-6">
							<div>
								<input id="ads-yes" type="radio" name="ads" value="0" checked />
								<label for="ads-yes">Yes</label> 
							</div>
							<div>
								<input id="ads-no" type="radio" name="ads" value="1" />
								<label for="ads-no">No</label> 
							</div>
						</div>
					</div>
					<!-- End Ads Field -->
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
			</div>


            <?php
    
        } elseif ($do == 'Insert') {

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'>Insert Category</h1>";
				echo "<div class='container'>";

                // Get Variables From The Form

				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$order 		= $_POST['ordering'];
				$visible 	= $_POST['visibility'];
				$comment 	= $_POST['commenting'];
				$ads 		= $_POST['ads'];

				// Check If Category Exist in Database

				$check = checkItem("Name", "category", $name);

				if ($check == 1) {

					$theMsg = '<div class="alert alert-danger">Sorry This Category Is Exist</div>';

					redirectHome($theMsg, 'back');

                } else {
                    // Insert Category in Databases
                    $stmt = $con->prepare("INSERT INTO 

						category(Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads)

					VALUES(:hname, :hdesc, :horder, :hvisible, :hcomment, :hads)");

					$stmt->execute(array(
						'hname' 	=> $name,
						'hdesc' 	=> $desc,
						'horder' 	=> $order,
						'hvisible' 	=> $visible,
						'hcomment' 	=> $comment,
						'hads'		=> $ads
					));

					// Echo Success Message

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';

                    redirectHome($theMsg, 'back');
                
                }    

                    

            } else {
                echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

				redirectHome($theMsg, 'back');

				echo "</div>";
            }
    
        } elseif ($do == 'Edit') {

			// Check If Get Request Catid Is Numeric & Get Its Integer Value
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM category WHERE ID = ?");
            $stmt->execute(array($catid));
            
            $cat = $stmt->fetch();
            
			$count = $stmt->rowCount();

            if ($count > 0) { ?>
				<h1 class="text-center">Edit Category</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="catid" value="<?php echo $catid ?>" />
						<!-- Start Name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Category" value="<?php echo $cat['Name'] ?>" />
							</div>
						</div>
						<!-- End Name Field -->
						<!-- Start Description Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="description" class="form-control" placeholder="Describe The Category" value="<?php echo $cat['Description'] ?>" />
							</div>
						</div>
						<!-- End Description Field -->
						<!-- Start Ordering Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Ordering</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" value="<?php echo $cat['Ordering'] ?>" />
							</div>
						</div>
						<!-- End Ordering Field -->
						<!-- Start Visibility Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Visible</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility'] == 0) { echo 'checked'; } ?> />
									<label for="vis-yes">Yes</label> 
								</div>
								<div>
									<input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) { echo 'checked'; } ?> />
									<label for="vis-no">No</label> 
								</div>
							</div>
						</div>
						<!-- End Visibility Field -->
						<!-- Start Commenting Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow Commenting</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0) { echo 'checked'; } ?> />
									<label for="com-yes">Yes</label> 
								</div>
								<div>
									<input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1) { echo 'checked'; } ?> />
									<label for="com-no">No</label> 
								</div>
							</div>
						</div>
						<!-- End Commenting Field -->
						<!-- Start Ads Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow Ads</label>
							<div class="col-sm-10 col-md-6">
								<div>
									<input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0) { echo 'checked'; } ?>/>
									<label for="ads-yes">Yes</label> 
								</div>
								<div>
									<input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1) { echo 'checked'; } ?>/>
									<label for="ads-no">No</label> 
								</div>
							</div>
						</div>
						<!-- End Ads Field -->
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
				echo "</div>";
            }
    
        } elseif ($do == 'Update') {

			echo '<h1 class="text-center">Update Category</h1>';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                // Get Variables From The Form
                $id 			= $_POST['catid'];
				$name 			= $_POST['name'];
				$desc 			= $_POST['description'];
				$order 			= $_POST['ordering'];
				$visible	   	= $_POST['visibility'];
				$comment 		= $_POST['commenting'];
                $ads 			= $_POST['ads'];

				// Update The Database With This Info
				$stmt = $con->prepare("UPDATE 
											category 
										SET 
											Name = ?, 
											Description = ?, 
											Ordering = ?, 
											Visibility = ?,
											Allow_Comment = ?,
											Allow_Ads = ? 
										WHERE 
											ID = ?");

				$stmt->execute(array($name, $desc, $order, $visible, $comment, $ads, $id));

				$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
				redirectHome($theMsg, 'back');

            } else {
                $theMsg = '<div class="alert alert-danger">you cant browse this page</div>';
                redirectHome($theMsg);
            }
    
        }elseif ($do == 'Delete') {

			echo "<h1 class='text-center'>Delete Category</h1>";
			echo "<div class='container'>";

				// Check If Get Request Catid Is Numeric & Get The Integer Value Of It

				$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('ID', 'category', $catid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM category WHERE ID = :hid");

					$stmt->bindParam(":hid", $catid);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

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