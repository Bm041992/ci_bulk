<div class="sidebar sidebar-main">
				<div class="sidebar-content">

					<!-- User menu -->
					<div class="sidebar-user">
						<div class="category-content">
							<div class="media">
								<a href="#" class="media-left"><img src="<?php echo base_url();?>assets/images/placeholder.jpg" class="img-circle img-sm" alt=""></a>
								<div class="media-body">
									<span class="media-heading text-semibold"><?php echo $this->session->userdata('user_name');?></span>
                                    <div class="text-size-mini text-muted">
										<i class="icon-pin text-size-small"></i> &nbsp;Santa Ana, CA
									</div>
								</div>

								<div class="media-right media-middle">
									<ul class="icons-list">
										<li>
											<a href="#"><i class="icon-cog3"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- /user menu -->

					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul class="navigation navigation-main navigation-accordion">

								<!-- Main -->
								<li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li>
								<li class="<?php if($this->uri->segment(1)=='dashboard') { echo 'active'; }?>">
                                    <a href="<?php echo base_url('dashboard');?>"><i class="icon-home4"></i> <span>Dashboard</span></a>
                                </li>
								
								<li class="<?php if($this->uri->segment(1)=='smtp_config') { echo 'active'; }?>">
                                    <a href="<?php echo base_url('smtp_config');?>"><i class="icon-list-unordered"></i> <span>SMTP Configuration </span></a>
                                </li>
								
							</ul>
						</div>
					</div>
					<!-- /main navigation -->

				</div>
			</div>