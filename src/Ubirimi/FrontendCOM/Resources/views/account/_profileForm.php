<form class="standard-form horizontal" name="profile" method="post" action="/account/profile/save">

    <?php if ($session->has('profile_updated')): ?>
        <div class="global-msg confirmation">
            Your profile info has been updated.
        </div>
    <?php endif ?>
    <div class="form-section clearfix">
        <h3>Company details</h3>

        <div class="section-left align-left sectionFeature blue">
            <fieldset>
                <label for="cname">Company name <span class="error">*</span></label>
                <input id="cname" type="text" name="company_name" value="<?php echo isset($_POST['company_name']) ? $_POST['company_name'] : $clientData['company_name'] ?>">
                <?php if ($errors['empty_company_name']): ?>
                    <br />
                    <p class="error">The company name can not be empty</p>
                <?php endif ?>
            </fieldset>

            <fieldset>
                <label for="cemail">Contact email <span class="error">*</span></label>
                <input id="cemail" type="text" name="contact_email" value="<?php echo isset($_POST['contact_email']) ? $_POST['contact_email'] : $clientData['contact_email'] ?>" />
                <?php if ($errors['empty_contact_email']): ?>
                    <br />
                    <p class="error">The contact email can not be emptyy</p>
                <?php elseif ($errors['contact_email_not_valid']): ?>
                    <br />
                    <p class="error">The email address you provided is not valid.</p>
                <?php elseif ($errors['contact_email_already_exists']): ?>
                    <br />
                    <p class="error">The email address you provided is already in use.</p>
                <?php endif ?>
            </fieldset>
        </div>
    </div>

    <div class="form-section clearfix">
        <h3>Organization details</h3>
        <div class="section-left align-left sectionFeature blue">
            <fieldset>
                <label for="caddress1">Address 1 <span class="error">*</span></label>
                <input id="caddress1" type="text" name="address_1" value="<?php echo isset($_POST['address_1']) ? $_POST['address_1'] : $clientData['address_1'] ?>" />
                <?php if ($errors['empty_address_1']): ?>
                    <br />
                    <span class="error">The company address can not be empty</span>
                <?php endif ?>
            </fieldset>

            <fieldset>
                <label for="caddress2">Address 2</label>
                <input id="caddress2" type="text" name="address_2" value="<?php echo isset($_POST['address_2']) ?  $_POST['address_2'] : $clientData['address_2'] ?>" />
            </fieldset>

            <fieldset>
                <label for="ccity">City <span class="error">*</span></label>
                <input id="ccity" type="text" name="city" value="<?php echo isset($request->request->get('city')) ? $request->request->get('city') : $clientData['city'] ?>" />
                <?php if ($errors['empty_city']): ?>
                    <br />
                    <span class="error">The city can not be empty</span>
                <?php endif ?>
            </fieldset>

            <fieldset>
                <label for="cdistrict">District <span class="error">*</span></label>
                <input id="cdistrict" type="text" name="district" value="<?php echo isset($request->request->get('district')) ? $request->request->get('district') : $clientData['district'] ?>" />
                <?php if ($errors['empty_district']): ?>
                    <br />
                    <span class="error">The district can not be empty</span>
                <?php endif ?>
            </fieldset>

            <fieldset>
                <label for="ccountry">Country <span class="error">*</span></label>
                <div class="custom-select-container">
                    <select id="ccountry" class="hasCustomSelect" name="country">
                        <option value="-1"></option>
                        <?php while ($country = $countries->fetch_array(MYSQLI_ASSOC)): ?>
                            <?php $textSelected = ''; ?>
                            <?php if (isset($request->request->get('country')) && $request->request->get('country') == $country['id']): ?>
                                <?php $textSelected = 'selected="selected"' ?>
                            <?php elseif ($clientData['sys_country_id'] == $country['id']): ?>
                                <?php $textSelected = 'selected="selected"' ?>
                            <?php endif ?>
                            <option <?php echo $textSelected ?> value="<?php echo $country['id'] ?>"><?php echo $country['name'] ?></option>
                        <?php endwhile ?>
                    </select>
                </div>
            </fieldset>
        </div>
    </div>
    <button type="submit" class="button_hp_small blue" name="update_company_profile">Update Information</button>
</form>