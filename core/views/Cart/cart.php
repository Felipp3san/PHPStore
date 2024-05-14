<script>
    $(document).ready(function() {
        $("#errorModal").modal('show');
        $("#successModal").modal('show');
    });
</script>
<div>
    <!-- ERRO -->
    <?php if (isset($_SESSION['error'])) : ?>
        <div id="errorModal" class="modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= $_SESSION['error-title'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body align-items-center d-flex">
                        <p class="m-0"><?= $_SESSION['error'] ?>
                        <p>
                    </div>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['error-title']); ?>
        <?php unset($_SESSION['error']); ?>
        <!-- SUCESSO -->
    <?php elseif (isset($_SESSION['success'])) : ?>
        <div id="successModal" class="modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= $_SESSION['success-title'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body align-items-center d-flex">
                        <p class="m-0"><?= $_SESSION['success'] ?>
                        <p>
                    </div>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['success-title']); ?>
        <?php unset($_SESSION['success']); ?>
    <?php endif ?>
</div>

<div class="container-fluid">
    <h2 class="mt-4 mb-4">Seu Carrinho</h2>
    <div class="row">
        <div class="col-md-8">
            <div class="product-card shadow p-3">
                <div class="card-body">
                    <!-- Lista de itens no carrinho -->
                    <h5 class="card-title mb-3">Itens no Carrinho</h5>
                    <ul class="list-group rounded-0">
                        <!-- ITEM CARRINHO -->
                        <?php if (isset($data['cart_items']) && $data['cart_items'] != false) : ?>
                            <li class="list-group-item">
                               <div class="row text-center">
                                    <div class="col-6"><span>Produto</span></div>
                                    <div class="col-2"><span>Preço</span></div>
                                    <div class="col-2"><span>Quantidade</span></div>
                                    <div class="col-2"><span>Preço Final</span></div>
                                </div>
                            </li>
                            <?php foreach ($data['cart_items'] as $item) : ?>
                                <li class="list-group-item">
                                    <div class="row d-flex justify-content-between align-items-center">
                                        <div class="col-2">
                                            <!-- IMAGEM -->
                                            <a href="?a=details&product-id=<?= $item->item_id ?>">
                                                <img src="assets/images/produtos/<?= substr($item->imagem, 0, strpos($item->imagem, "@")) ?>" class="img-thumbnail border-0" width="150" height="150" alt="<? $product->nome ?>">
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <!-- TITULO -->
                                            <span class="fw-semibold"><?= $item->nome ?></span>
                                        </div>
                                        <div class="col-2">
                                            <span class="lead fs-4 d-flex justify-content-center"><?= $item->preco ?> €</span>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex justify-content-center">
                                                <!-- REMOVER DO CARRINHO -->
                                                <form action="?a=remove_from_cart" method="POST">
                                                    <input type="hidden" name="item-id" value="<?= $item->item_id ?>">
                                                    <input type="hidden" name="quantity" value="<?= $item->quantidade ?>">
                                                    <button class="btn btn-secondary rounded-0 fs-5 p-0 border-2" id="decrease">-</button>
                                                </form>
                                                <!-- QUANTIDADE -->
                                                <input type="text" class="form-control rounded-0 fs-5 p-0 border-2" id="quantity" value="<?= $item->quantidade ?>" disabled>
                                                <!-- ADICIONAR NO CARRINHO -->
                                                <form action="?a=add_to_cart" method="POST">
                                                    <input type="hidden" name="actual-url" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                                    <input type="hidden" name="item-id" value="<?= $item->item_id ?>">
                                                    <input type="hidden" name="item-price" value="<?= $item->item_preco ?>">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button class="btn btn-secondary rounded-0 fs-5 p-0 border-2" id="increase">+</button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-2 d-grid justify-content-center">
                                            <!-- PRECO TOTAL -->
                                            <span class="lead fw-medium fs-4 d-flex justify-content-center"><?= $item->preco * $item->quantidade ?> €</span>
                                        </div>
                                        <!-- SOMAR TOTAL DOS PRODUTOS -->
                                        <?php $total = (!isset($total))? $item->preco * $item->quantidade : $total + $item->preco * $item->quantidade ?>
                                    </div>
                                </li>
                            <?php endforeach ?>
                        <?php else: ?>
                            <span class="lead">Seu carrinho está vazio.</span>
                        <?php endif ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="product-card shadow p-3">
                <div class="card-body">
                    <!-- Resumo do carrinho -->
                    <h5 class="card-title mb-3">Resumo do Pedido</h5>
                    <ul class="list-group rounded-0">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Subtotal
                            <span><?php echo $total = (!isset($total))? 0 : $total ?> €</span>                        
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Frete
                            <span>Grátis</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Total</strong>
                            <strong><?php echo $total = (!isset($total))? 0 : $total ?> €</strong>
                        </li>
                    </ul>
                    <!-- Botões de ação -->
                    <div class="d-grid mt-3">
                        <button type="button" class="btn btn-primary btn-block rounded-0">Finalizar Compra</button>
                        <button type="button" class="btn btn-secondary btn-block mt-2 rounded-0">Continuar Comprando</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>