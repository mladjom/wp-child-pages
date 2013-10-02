		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', $this->plugin_slug ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		<!-- Widget Image Size: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_size' ); ?>"><?php _e( 'Image Size (in pixels):', $this->plugin_slug ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'thumb_size' ); ?>"  value="<?php echo $instance['thumb_size']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'thumb_size' ); ?>" />
		</p>		
		<!-- Widget Order By: Select Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order By:', $this->plugin_slug ); ?></label>
			<select name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>">
			<?php foreach ( $this->get_orderby_options() as $k => $v ) { ?>
				<option value="<?php echo $k; ?>"<?php selected( $instance['orderby'], $k ); ?>><?php echo $v; ?></option>
			<?php } ?>
			</select>
		</p>		
		<!-- Widget Order: Select Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order Direction:', $this->plugin_slug ); ?></label>
			<select name="<?php echo $this->get_field_name( 'order' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>">
			<?php foreach ( $this->get_order_options() as $k => $v ) { ?>
				<option value="<?php echo $k; ?>"<?php selected( $instance['order'], $k ); ?>><?php echo $v; ?></option>
			<?php } ?>
			</select>
		</p>
		<!-- Widget Show Title: Checkbox Input -->
		<p>
			<label for="<?php echo $this->get_field_id("show_title"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_title"); ?>" name="<?php echo $this->get_field_name("show_title"); ?>"<?php checked( (bool) $instance["show_title"], true ); ?> />
				<?php _e( 'Show page title', $this->plugin_slug ); ?>
			</label>
		</p>
		<!-- Widget Show Excerpt: Checkbox Input -->
		<p>
			<label for="<?php echo $this->get_field_id("show_excerpt"); ?>">
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_excerpt"); ?>" name="<?php echo $this->get_field_name("show_excerpt"); ?>"<?php checked( (bool) $instance["show_excerpt"], true ); ?> />
				<?php _e( 'Show excerpt', $this->plugin_slug ); ?>
			</label>
		</p>	
		<!-- Excerpt Limit: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'excerpt_lenght' ); ?>"><?php _e( 'Excerpt length (characters):', $this->plugin_slug ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'excerpt_lenght' ); ?>"  value="<?php echo $instance['excerpt_lenght']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'excerpt_lenght' ); ?>" />	
		</p>	