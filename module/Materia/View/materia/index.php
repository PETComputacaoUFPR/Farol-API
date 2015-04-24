<header>
    <h1>Farol</h1>
    <h3>Matérias</h3>
    <a class="button normal float-right float-button margin-horizontal hover-shadow" 
        href="?module=materia&controller=materia&action=create">
        Nova <i class="fa fa-plus fa-lg"></i>
    </a>
</header>
<section class='padding'>
    <table border ='0' class='center-block'>
    	<thead>
    		<tr>
    			<th>Código</th>
    			<th>Nome</th>
    			<th colspan="2">Ação</th>
    		</tr>
    	</thead>
    	<?php
    	foreach ($this->data['materias'] as $materia){	
    	?>
    	
    	<tr>
    	<td class="padding-vertical"><?php echo $materia->getCodigo()?></td>
    	<td class="padding-vertical"><?php echo $materia->getNome()?></td>
    	<td class="padding-vertical">
    	    <a class="button normal hover-shadow" href='?module=materia&controller=materia&action=update&codigo=<?php echo $materia->getCodigo()?>'>
    	        <i class="fa fa-edit fa-fw"></i>
    	    </a>
    	</td>
    	<td class="padding-vertical">
    	    <a class='button red hover-shadow' href='?module=materia&controller=materia&action=delete&codigo=<?php echo $materia->getCodigo()?>'>
    	        <i class="fa fa-close fa-fw"></i>
    	    </a>
    	</td>
    	</tr>
    	
    	<?php
    	}
    	?>
    </table>
</section>