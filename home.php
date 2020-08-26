<?php
$step = new Step();

$current = $step->getCurrentStep();

$currentItem = Step::getCurrentItem();

$step::updateSession();
?>

<div class="container p-0">
    <div class="d-flex justify-content-center mt-5">

        <?php if (Session::get('currentStep') === 1): ?>
            <form method="post">
                <div class="form-group">
                    <label for="answer"><?php echo 'Pense em um prato que gosta'; ?></label>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-sm btn-primary mr-2" name="step" value="1">Ok</button>
                </div>
            </form>

        <?php elseif (Session::get('currentStep') === 2): ?>
            <form method="post">
                <div class="form-group">
                    <label for="answer"><?php echo $step->getMessage(); ?></label>
                </div>
                <div class="d-flex justify-content-center">
                    <?php if (Session::get('finished') == 0): ?>
                        <button type="submit" class="btn btn-sm btn-primary mr-2 step-2" name="answer" value="true">
                            Sim
                        </button>
                        <button type="submit" class="btn btn-sm btn-danger ml-2 step-2" name="answer" value="false">
                            Não
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-sm btn-info mr-2 step-2" name="answer" value="">
                            Ok
                        </button>
                    <?php endif; ?>
                </div>
            </form>

        <?php elseif (Session::get('currentStep') === 3): ?>

            <form method="post">
                <div class="form-group first">
                    <label for="dish">Qual prato você pensou?</label>
                    <input type="text" maxlength="200" class="form-control" id="dish" name="dish" placeholder="" autocomplete="off">
                    <div class="d-flex justify-content-center mt-3 ">
                        <button type="button" class="btn btn-sm btn-primary mr-2 first-validate">
                            Ok
                        </button>
                        <button type="button" class="btn btn-sm btn-danger ml-2 first-cancel">
                            Cancelar
                        </button>
                    </div>
                </div>


                <div class="form-group second">
                    <label for="category"><span id="dish-text">VALORCAMPO</span> é __________ mas <?php echo Step::getNameItem($currentItem); ?> não
                    <input type="text" maxlength="200" class="form-control" id="category" name="category" placeholder="" autocomplete="off">
                    <div class="d-flex justify-content-center mt-3">
                        <button type="button" class="btn btn-sm btn-primary mr-2 second-validate">
                            Ok
                        </button>
                        <button type="button" class="btn btn-sm btn-danger ml-2 second-cancel">
                            Cancelar
                        </button>
                    </div>
                </div>

            </form>

        <?php endif; ?>

    </div>
</div>