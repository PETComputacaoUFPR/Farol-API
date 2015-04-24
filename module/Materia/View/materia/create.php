<header>
    <h1>Farol</h1>
    <h3><?php echo (($_GET['action'] == 'create')? 'Cadastrar' : 'Atualizar'); ?> matéria</h3>
</header>
<section class="padding">
    <form method="POST">
        <label for="codigo">Código: </label>
        <input type="text" name="codigo" id="codigo" value="<?php echo $this->data['materia']->getCodigo() ?>" required>
        <br/>
        <label for="nome">Nome: </label>
        <input type="text" name="nome" id="nome" value="<?php echo $this->data['materia']->getNome() ?>" required>
        <br/>
        <input class="button normal hover-shadow" type="submit" value="Cadastrar">
    </form>
</section>