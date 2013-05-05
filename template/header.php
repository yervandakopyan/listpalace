<?	
	session_start();
	
	
	$path = $_SERVER['DOCUMENT_ROOT'];
	$path .= "/classes/autoloader.php";
	require_once($path);
	
	
	try {
		if ($_POST['cmd'] == 'log_out') {
			session_destroy();
			header( 'Location: ../../login.php' );
		}
	} catch(Exception $e) {
		$error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
	}
	
	try {
		if(!($_SESSION['id_user'])) {
			$link = "<a href='/login.php'>Login</a> or <a href='/register/register.php'>Register</a>";
		} else {
			$server = $_SERVER['PHP_SELF'];
			$link = "<form method='post' action='{$server}'>
						<input type='hidden' name='cmd' id='cmd' value='log_out' />
						<input id='log_out' type='submit' value='Log Out' />
					</form>";
		}
	} catch(Exception $e) {
		$error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
	}
	
	
	$category=new category();
	$categories = $category->getCategories();
	
	
	
?>
	
	<div class="extra">
    	<!--==============================header=================================-->
        <header>
        	<div class="row-top">
            	<div class="main">
                	<div class="wrapper">
                    	<h1><a href="index.html">LOGO</a></h1>
                        <!--form id="search-form" method="post" enctype="multipart/form-data">
                        <fieldset>	
                            <div class="search-field">
                                <input name="search" type="text" value="Search..." onBlur="if(this.value=='') this.value='Search...'" onFocus="if(this.value =='Search...' ) this.value=''" />
                                <a class="search-button" href="#" onClick="document.getElementById('search-form').submit()"></a>	
                            </div>						
                        </fieldset>
                    </form-->
                    </div>
                </div>
				
				<div class="registration">
					<ul>
						<li>
							<?php echo $link; ?>
						</li>
						<span> </span>
					</ul>
				</div>	
            </div>
            <div class="menu-row">
            	<div class="menu-bg">
                    <div class="main">
                        <nav class="indent-left">
                            <ul class="menu wrapper">
								<div class="clear"></div>
								<li name="menu_hover">
									<span>
										<a href="">Jobs</a>
									</span>
									<div id="anonymous_element_1" class="nav-box-1 navmar" style="display: none;" name="menu_content">
										<div class="nav-content">
											<ul class="nav-menu">
												<?php
													foreach ($categories as $cat) {
													$cat_display = str_replace (" ", "-", $cat['display_name']);
													$cat_url = "/contractor-jobs/" . strtolower(str_replace("-&-","-",$cat_display)) . "/" ;
													$form_id = "category_" . $cat['id_main_category'];
													$link = "<li class='border_first_column'>" . 
																"<a href='{$cat_url}'>" . $cat['display_name'] . "</a>".
															"</li>";
													
													echo $link;		
													}
												?>
											</ul>
										</div>
									</div>
								</li>

                                <li><a href="company.html">our Company</a></li>
                                <li><a href="services.html">our services</a></li>
                                <li><a href="projects.html">our projects</a></li>
                                <li><a href="contact.html">Contact Us</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        
        <!--==============================content================================-->