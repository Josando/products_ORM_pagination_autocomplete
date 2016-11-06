<body class="homepage">

<!-- Header -->
		<div id="header">
			<div id="nav-wrapper">
				<!-- Nav -->
				<nav id="nav">
					<ul>
						<li class="active"><a href="index.php?module=main">Homepage</a></li>
						<li><a href="index.php?module=products&view=create_products">Products</a></li>
						<li><a href="index.php?module=products_FE&view=list_products">List products</a></li>
						<li><a href="left-sidebar.html">Left Sidebar</a></li>
						<li><a href="right-sidebar.html">Right Sidebar</a></li>
						<li><a href="index.php?module=about">About</a></li>

					</ul>
				</nav>
			</div>
			<div class="container">

				<!-- Logo -->
				<div id="logo">
					<h1><a href="#">Top padel</a></h1>
					<span class="tag">Makes you improve</span>
				</div>
			</div>
		</div>

		<section id="title" class="emerald">
    <div class="container">
        <div class="row">
             <div >
                <h1><?php
                    if (!isset($_GET['module'])) {
                        echo "Home";
                    } else if (isset($_GET['module']) && !isset($_GET['view'])) {
                        echo "<a href='index.php?module=" . $_GET['module'] . "'>" . $_GET['module'] . "</a>";
                    }else{
                        echo "<a href='index.php?module=" . $_GET['module'] . "&view=".$_GET['view']."'>" . $_GET['module'] . "</a>";
                    }
                    ?></h1>
                <strong>Web de test del proyecto</strong>

            </div>
             <h2 class="BackHome"><a href="index.php">Back Home</a></h2>
        </div>
    </div>
</section>
