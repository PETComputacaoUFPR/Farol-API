<header class="header-search">
    <h2><a href="?module=application&controller=home&action=index"><i class="fa fa-anchor"></i> Farol</a></h2>
    <form method="post">
		<input type="search" placeholder="O que você procura?" name="search" class="search-bar" value="<?php echo $this->data['search'];?>" required>
		<input type="submit" class="icon-button red" value="&#xf002;">
	</form>
</header>
<section>
    <p>Não há nenhum resultado</p>
</section>