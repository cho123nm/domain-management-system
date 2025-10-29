<?php
include_once('Config/Header.php');
include_once(__DIR__.'/Repositories/DomainRepository.php');
$domainRepo = new DomainRepository($connect);
?>
				
				
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
				
					<div class="app-container container-xxl d-flex flex-row flex-column-fluid">
					
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
					
							<div class="d-flex flex-column flex-column-fluid">
								
						
						
						
						
						
						
                         <div id="kt_app_content" class="app-content flex-column-fluid">
									
									<div class="card mb-5 mb-xl-10">
										
									</div>
								
									
									
									<div class="row gy-5 g-xl-10">
									
										<div class="col-xl-4">
										
											<div class="card card-flush h-xl-60">
										
												<div class="card-header pt-7">
												
													<h3 class="card-title align-items-start flex-column">
														<span class="card-label fw-bold text-dark">Loại Miền</span>
														<span class="text-gray-400 mt-1 fw-semibold fs-6">Các Đuôi Miền Đang Bán</span>
													</h3>
												
												</div>
											
												<div class="card-body">
											
													<div class="hover-scroll-overlay-y pe-6 me-n6" style="height: 345px">
												
												<!---->
												
												
												
												   	<?php
                                        		$domains = $domainRepo->listAll();
                                        		foreach ($domains as $thanhdepchai){
                                        			?>
                                        			
												
														<div class="border border-dashed border-gray-300 rounded px-7 py-3 mb-6">
														
															<div class="d-flex flex-stack mb-3">
													
																<div class="me-3">
																	
																	&emsp;
																	<img src="<?=$thanhdepchai['image'];?>" class="w-50px ms-n1 me-1" alt="">
														
																</div>
																<div class="m-0">
													              	<span class="badge badge-light-success"> <?=number_format($thanhdepchai['price']);?>Đ </span>
																</div>
															</div>
														
															<div class="d-flex flex-stack">
																<span class="text-gray-400 fw-bold"> Không Bảo Hành </span>
															</div>
														</div>
														
														
														
                                        			<?php } ?>
														
														
														
														
													</div>
												
												</div>
												
											</div>
										
										</div>
									
									
									
									
									
									
									
								
										<div class="col-xl-8">
										
										
										
										
										<div class="card card-flush h-lg-20" id="kt_contacts_main">
											
												<div class="card-header pt-7" id="kt_chat_contacts_header">
												
													<div class="card-title">
														
														<span class="svg-icon svg-icon-1 me-2">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z" fill="currentColor"></path>
																<path opacity="0.3" d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z" fill="currentColor"></path>
															</svg>
														</span>
														<!--end::Svg Icon-->
														<h2> Kiểm Tra Tên Miền </h2>
													</div>
											
												</div>
										
										          
										         
										          
										           
												<div class="card-body pt-5">
												
													<div class="form fv-plugins-bootstrap5 fv-plugins-framework">
													
														<div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
														
															<div class="col">
																
																<div class="fv-row mb-7 fv-plugins-icon-container">
																	
																	<label class="fs-6 fw-semibold form-label mt-3">
																		<span class="required"> Tên Miền </span>
																		<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" aria-label="Enter the contact's email." data-bs-original-title="Enter the contact's email." data-kt-initialized="1"></i>
																	</label>
																	
																	
																	<input type="text" class="form-control form-control-solid" placeholder="Nhập Tên Miền" id="name">
																	
																<div class="fv-plugins-message-container invalid-feedback"></div></div>
																
															</div>
															
															<div class="col">
																
																<div class="fv-row mb-7">
																	
																	<label class="fs-6 fw-semibold form-label mt-3">
																		<span> Đuôi Miền </span>
																		<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" aria-label="Enter the contact's phone number (optional)." data-bs-original-title="Enter the contact's phone number (optional)." data-kt-initialized="1"></i>
																	</label>
																	
																	
																	<select class="form-select" id="domain">
																	   	<?php
                                        		$domains = $domainRepo->listAll();
                                        		foreach ($domains as $thanhdepchai){
                                        			?>
                                        			<option value="<?=$thanhdepchai['duoi'];?>"><?=$thanhdepchai['duoi'];?></option>
                                        			
                                                                    <?php } ?>
																	
																	</select>
																	
																	
																	
																</div>
																</div>
															
															<div id="status"></div>
															
															
																<div class="fv-row mb-7">
																    
														<div class="d-flex justify-content-end">


	                                                     	
														

                                                           

															<button type="reset" data-kt-contacts-type="cancel" class="btn btn-light me-3">Cancel</button>
														
															<button type="submit" id="whois" class="btn btn-primary">
																<span class="indicator-label"> Kiểm Tra </span>
																<span class="indicator-progress">Please wait... 
																<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
															</button>
															
														</div>
												
													</div>
													</div>	</div>
												<br>
												
											
										       
										          
												
												</div>
								
											</div></div></div>
										
										
										
						
						
						
						
						
								<!--end::Content-->
							</div>
							<!--end::Content wrapper-->

		
		
	   <script>
       $(document).ready(function() {
        $('#whois').on('click', function() {
        $("#whois").text('Đang xử lý...');
            var domain = $('#domain').val();
            var name = $('#name').val();
            $.ajax({
                url: '/Ajaxs/CheckDomain.php',
                type: 'POST',
                data: {name:name,domain:domain},
                success:function(data){
                 $("#whois").attr("disabled", false);
                 $("#whois").text('Kiểm Tra');
                 $('#status').html(data);
                }
            }); 
        });
    });
</script>

				
                                        
                                        		<?php
                                        include('Config/Footer.php');
                                        ?>
                                        				
                                        				