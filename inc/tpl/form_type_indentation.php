<label for="type_indentation">Type d’indentation</label>
<select name="options[indentation]" id="type_indentation">
    <?php foreach($HTML_Lisible->types_indentation as $key => $option): ?>
    <option <?php 
	echo ($HTML_Lisible->user_options['indentation'] == $key ? 'selected="selected"' : ''); 
	?> value="<?php echo $key; ?>"><?php echo $option[1]; ?></option>
    <?php endforeach; ?>
</select>

