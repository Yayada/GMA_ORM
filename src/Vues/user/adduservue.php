<?php class AdduserVue extends VueAll{public function adduser(){ $this->content .="<form style='width:50%; margin:20px'><div class='form-group'>
                            <label for='id'>id</label>
                            <input type='text' class='form-control' placeholder='id'>
                        </div><div class='form-group'>
                            <label for='nom'>nom</label>
                            <input type='text' class='form-control' placeholder='nom'>
                        </div><div class='form-group'>
                            <label for='email'>email</label>
                            <input type='text' class='form-control' placeholder='email'>
                        </div><div class='form-group'>
                            <label for='tel'>tel</label>
                            <input type='text' class='form-control' placeholder='tel'>
                        </div><button type='submit' class='btn btn-default'>Annuler</button>
                    <button type='submit' class='btn btn-primary'>Ajouter</button>
                </form>;}