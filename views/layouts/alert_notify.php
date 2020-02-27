<?php

if (Yii::$app->session->hasFlash('success')) {
    $flashMessageList = Yii::$app->session->getFlash('success');
    if (\is_string($flashMessageList)) {
        $flashMessageList = str_replace('\\', '\\\\', $flashMessageList);
        $this->registerJs("$.notify({message : '{$flashMessageList}'},{type: 'success'});", $this::POS_READY);
    }
    if (\is_array($flashMessageList)) {
        foreach ($flashMessageList as $flash) {
            if (\is_string($flash)) {
                $flash = str_replace('\\', '\\\\', $flash);
                $this->registerJs("$.notify({message : '{$flash}'},{type: 'success'});", $this::POS_READY);
            }
            if (\is_array($flash)) {
                foreach ($flash as $error) {
                    if (\is_string($error)) {
                        $error = str_replace('\\', '\\\\', $error);
                        $this->registerJs("$.notify({message : '{$error}'},{type: 'success'});", $this::POS_READY);
                    }
                    if (\is_array($error)) {
                        foreach ($error as $item) {
                            $item = str_replace('\\', '\\\\', $item);
                            $this->registerJs("$.notify({message : '{$item}'},{type: 'success'});", $this::POS_READY);
                        }
                    }
                }
            }
        }
    }
}


if (Yii::$app->session->hasFlash('error')) {
    $errors = Yii::$app->session->getFlash('error');

    if (\is_string($errors)) {
        $errors = str_replace('\\', '\\\\', $errors);
        $this->registerJs("$.notify({message : '{$errors}'},{type: 'danger'});", $this::POS_READY);
    }
    if (\is_array($errors)) {
        foreach ($errors as $flash) {
            if (\is_string($flash)) {
                $flash = str_replace('\\', '\\\\', $flash);
                $this->registerJs("$.notify({message : '{$flash}'},{type: 'danger'});", $this::POS_READY);
            }
            if (\is_array($flash)) {
                foreach ($flash as $error) {
                    if (\is_string($error)) {
                        $error = str_replace('\\', '\\\\', $error);
                        $this->registerJs("$.notify({message : '{$error}'},{type: 'danger'});", $this::POS_READY);
                    }
                    if (\is_array($error)) {
                        foreach ($error as $item) {
                            $item = str_replace('\\', '\\\\', $item);
                            $this->registerJs("$.notify({message : '{$item}'},{type: 'danger'});", $this::POS_READY);
                        }
                    }
                }
            }
        }
    }
}

