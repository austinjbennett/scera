<?php

function wp_salt( $scheme = 'auth' ) {
    static $cached_salts = array();
    if ( isset( $cached_salts[ $scheme ] ) ) {
            return $cached_salts[ $scheme ];
    }
    static $duplicated_keys;
    if ( null === $duplicated_keys ) {
            $duplicated_keys = array( 'put your unique phrase here' => true );
            foreach ( array( 'AUTH', 'SECURE_AUTH', 'LOGGED_IN', 'NONCE', 'SECRET' ) as $first ) {
                    foreach ( array( 'KEY', 'SALT' ) as $second ) {
                            if ( ! defined( "{$first}_{$second}" ) ) {
                                    continue;
                            }
                            $value = constant( "{$first}_{$second}" );
                            $duplicated_keys[ $value ] = isset( $duplicated_keys[ $value ] );
                    }
            }
    }
    $values = array(
            'key' => '',
            'salt' => ''
    );
    if ( defined( 'SECRET_KEY' ) && SECRET_KEY && empty( $duplicated_keys[ SECRET_KEY ] ) ) {
            $values['key'] = SECRET_KEY;
    }
    if ( 'auth' == $scheme && defined( 'SECRET_SALT' ) && SECRET_SALT && empty( $duplicated_keys[ SECRET_SALT ] ) ) {
            $values['salt'] = SECRET_SALT;
    }
    if ( in_array( $scheme, array( 'auth', 'secure_auth', 'logged_in', 'nonce' ) ) ) {
            foreach ( array( 'key', 'salt' ) as $type ) {
                    $const = strtoupper( "{$scheme}_{$type}" );
                    if ( defined( $const ) && constant( $const ) && empty( $duplicated_keys[ constant( $const ) ] ) ) {
                            $values[ $type ] = constant( $const );
                    } elseif ( ! $values[ $type ] ) {
                            $values[ $type ] = get_option( "{$scheme}_{$type}" );
                            if ( ! $values[ $type ] ) {
                                    throw new Exception('Error retreiving salt');
                            }
                    }
            }
    } else {
            if ( ! $values['key'] ) {
                    $values['key'] = get_option( 'secret_key' );
                    if ( ! $values['key'] ) {
                            throw new Exception('Error retreiving salt');
                    }
            }
            $values['salt'] = hash_hmac( 'md5', $scheme, $values['key'] );
    }
    $cached_salts[ $scheme ] = $values['key'] . $values['salt'];

    return $cached_salts[ $scheme ];
}

function wp_hash($data, $scheme = 'auth') {
    $salt = wp_salt($scheme);
    return hash_hmac('md5', $data, $salt);
}

function stripslashes_from_strings_only( $value ) {
    return is_string( $value ) ? stripslashes( $value ) : $value;
}
function wp_unslash($value, $callback='stripslashes_from_strings_only'){
    if ( is_array( $value ) ) {
            foreach ( $value as $index => $item ) {
                    $value[ $index ] = wp_unslash( $item, $callback );
            }
    } elseif ( is_object( $value ) ) {
            $object_vars = get_object_vars( $value );
            foreach ( $object_vars as $property_name => $property_value ) {
                    $value->$property_name = wp_unslash( $property_value, $callback );
            }
    } else {
            $value = call_user_func( $callback, $value );
    }
    return $value;
}

function wp_rand( $min = 0, $max = 0 ) {
    global $rnd_value;

    // Some misconfigured 32bit environments (Entropy PHP, for example) truncate integers larger than PHP_INT_MAX to PHP_INT_MAX rather than overflowing them to floats.
    $max_random_number = 3000000000 === 2147483647 ? (float) "4294967295" : 4294967295; // 4294967295 = 0xffffffff

    // We only handle Ints, floats are truncated to their integer value.
    $min = (int) $min;
    $max = (int) $max;

    // Use PHP's CSPRNG, or a compatible method
    static $use_random_int_functionality = true;
    if ( $use_random_int_functionality ) {
        try {
            $_max = ( 0 != $max ) ? $max : $max_random_number;
            // wp_rand() can accept arguments in either order, PHP cannot.
            $_max = max( $min, $_max );
            $_min = min( $min, $_max );
            $val = random_int( $_min, $_max );
            if ( false !== $val ) {
                return absint( $val );
            } else {
                $use_random_int_functionality = false;
            }
        } catch ( Error $e ) {
            $use_random_int_functionality = false;
        } catch ( Exception $e ) {
            $use_random_int_functionality = false;
        }
    }

    // Reset $rnd_value after 14 uses
    // 32(md5) + 40(sha1) + 40(sha1) / 8 = 14 random numbers from $rnd_value
    if ( strlen($rnd_value) < 8 ) {
        static $seed = '';
        $rnd_value = md5( uniqid(microtime() . mt_rand(), true ) . $seed );
        $rnd_value .= sha1($rnd_value);
        $rnd_value .= sha1($rnd_value . $seed);
        $seed = md5($seed . $rnd_value);
    }

    // Take the first 8 digits for our value
    $value = substr($rnd_value, 0, 8);

    // Strip the first eight, leaving the remainder for the next call to wp_rand().
    $rnd_value = substr($rnd_value, 8);

    $value = abs(hexdec($value));

    // Reduce the value to be within the min - max range
    if ( $max != 0 )
        $value = $min + ( $max - $min + 1 ) * $value / ( $max_random_number + 1 );

    return abs(intval($value));
  }

function wp_generate_password( $length = 12, $special_chars = true, $extra_special_chars = false ) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        if ( $special_chars )
                $chars .= '!@#$%^&*()';
        if ( $extra_special_chars )
                $chars .= '-_ []{}<>~`+=,.;:/?|';

        $password = '';
        for ( $i = 0; $i < $length; $i++ ) {
                $password .= substr($chars, wp_rand(0, strlen($chars) - 1), 1);
        }

        return $password;
}


function is_serialized( $data, $strict = true ) {
        // if it isn't a string, it isn't serialized.
        if ( ! is_string( $data ) ) {
                return false;
        }
        $data = trim( $data );
        if ( 'N;' == $data ) {
                return true;
        }
        if ( strlen( $data ) < 4 ) {
                return false;
        }
        if ( ':' !== $data[1] ) {
                return false;
        }
        if ( $strict ) {
                $lastc = substr( $data, -1 );
                if ( ';' !== $lastc && '}' !== $lastc ) {
                        return false;
                }
        } else {
                $semicolon = strpos( $data, ';' );
                $brace     = strpos( $data, '}' );
                // Either ; or } must exist.
                if ( false === $semicolon && false === $brace )
                        return false;
                // But neither must be in the first X characters.
                if ( false !== $semicolon && $semicolon < 3 )
                        return false;
                if ( false !== $brace && $brace < 4 )
                        return false;
        }
        $token = $data[0];
        switch ( $token ) {
                case 's' :
                        if ( $strict ) {
                                if ( '"' !== substr( $data, -2, 1 ) ) {
                                        return false;
                                }
                        } elseif ( false === strpos( $data, '"' ) ) {
                                return false;
                        }
                        // or else fall through
                case 'a' :
                case 'O' :
                        return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
                case 'b' :
                case 'i' :
                case 'd' :
                        $end = $strict ? '$' : '';
                        return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
        }
        return false;
}
function maybe_unserialize( $original ) {
        if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
                return @unserialize( $original );
        return $original;
}
function maybe_serialize( $data ) {
        if ( is_array( $data ) || is_object( $data ) )
                return serialize( $data );

        // Double serialization is required for backward compatibility.
        // See https://core.trac.wordpress.org/ticket/12930
        // Also the world will end. See WP 3.6.1.
        if ( is_serialized( $data, false ) )
                return serialize( $data );

        return $data;
}

function update_user_meta_cache($object_ids) {
        global $mysqli, $table_prefix;

        $meta_type = 'user';
        $table = $table_prefix.'usermeta';
        $column = sanitize_key($meta_type . '_id');

        if ( ! $object_ids ) {
            return false;
        }
        if ( !is_array($object_ids) ) {
            $object_ids = preg_replace('|[^0-9,]|', '', $object_ids);
            $object_ids = explode(',', $object_ids);
        }
        $object_ids = array_map('intval', $object_ids);

        $ids = array();
        $cache = array();
        foreach ( $object_ids as $id ) {
            $ids[] = $id;
        }

        // Get meta info
        $id_list = join( ',', $ids );
        $id_column = 'user' == $meta_type ? 'umeta_id' : 'meta_id';

        $stmt = $mysqli->prepare("SELECT $column, meta_key, meta_value FROM $table WHERE $column IN ($id_list) ORDER BY $id_column ASC");
        $stmt->execute();
        $meta_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        //$meta_list = $wpdb->get_results( "SELECT $column, meta_key, meta_value FROM $table WHERE $column IN ($id_list) ORDER BY $id_column ASC", ARRAY_A );

        if ( !empty($meta_list) ) {
                foreach ( $meta_list as $metarow) {
                        $mpid = intval($metarow[$column]);
                        $mkey = $metarow['meta_key'];
                        $mval = $metarow['meta_value'];

                        // Force subkeys to be array type:
                        if ( !isset($cache[$mpid]) || !is_array($cache[$mpid]) )
                                $cache[$mpid] = array();
                        if ( !isset($cache[$mpid][$mkey]) || !is_array($cache[$mpid][$mkey]) )
                                $cache[$mpid][$mkey] = array();

                        // Add a value to the current pid/key:
                        $cache[$mpid][$mkey][] = $mval;
                }
        }

        foreach ( $ids as $id ) {
                if ( ! isset($cache[$id]) )
                        $cache[$id] = array();
        }

        return $cache;
}

function get_user_meta($object_id, $meta_key='', $single=false){

        if ( ! is_numeric( $object_id ) ) {
                return false;
        }

        $object_id = abs((int)$object_id);
        if ( ! $object_id ) {
                return false;
        }

        $meta_cache = update_user_meta_cache( array( $object_id ) )[$object_id];
        if ( isset($meta_cache[$meta_key]) ) {
                if ( $single )
                        return maybe_unserialize( $meta_cache[$meta_key][0] );
                else
                        return array_map('maybe_unserialize', $meta_cache[$meta_key]);
        }

        if ( ! $meta_key ) {
                return $meta_cache;
        }


        if ($single)
                return '';
        else
                return array();
}

function sanitize_key( $key ) {
        $key = strtolower( $key );
        $key = preg_replace( '/[^a-z0-9_\-]/', '', $key );

        return $key;
}

function add_user_metadata( $object_id, $meta_key, $meta_value) {
        global $mysqli, $table_prefix;

        $meta_subtype = $meta_type = 'user';

        if ( ! $meta_key || ! is_numeric( $object_id ) ) {
            return false;
        }

        $object_id = abs((int)( $object_id ));
        $table = $table_prefix.'usermeta';

        $column = sanitize_key($meta_type . '_id');

        // expected_slashed ($meta_key)
        $meta_key = wp_unslash($meta_key);
        $meta_value = wp_unslash($meta_value);
        $meta_value = maybe_serialize( $meta_value );

        $result = $mysqli->store_result();
        $sql = "INSERT INTO $table (user_id, meta_key, meta_value) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        //var_dump(mysqli_error($mysqli));
        $stmt->bind_param("sss", $object_id,  $meta_key, $meta_value);
        $stmt->execute();
        //var_dump($stmt->get_result());
        //$arr = $stmt->get_result()->fetch_row()[0];
        //if(!$arr) return false;
        $stmt->close();

        return (int)$mysqli->insert_id;
	}

function update_user_meta($user_id, $meta_key, $meta_value, $prev_value='') {

        // if (CREATING_NEW_USER){
        //     return add_user_metadata($meta_key, $meta_value, $prev_value='');
        // }

        global $mysqli, $table_prefix;
        $meta_type = 'user';

        if ( ! $meta_key || ! is_numeric( $user_id ) ) {
            return false;
        }

        $user_id = abs((int)$user_id);
        if ( ! $user_id ) {
            return false;
        }

        $table = $table_prefix.'usermeta';
        $column = sanitize_key($meta_type . '_id');

        // expected_slashed ($meta_key)
        $raw_meta_key = $meta_key;
        $meta_key = wp_unslash($meta_key);
        $passed_value = $meta_value;
        $meta_value = wp_unslash($meta_value);

        // // Compare existing value to new value if no prev value given and the key exists only once.
        // if ( empty($prev_value) ) {
        //     $old_value = get_user_meta($user_id, $meta_key);
        //     if ( count($old_value) == 1 ) {
        //         if ( $old_value[0] === $meta_value )
        //             return false;
        //     }
        // }

        $stmt = $mysqli->prepare("SELECT umeta_id FROM $table WHERE meta_key=? AND user_id=?");
        $stmt->bind_param("si", $meta_key, $user_id);
        $stmt->execute();
        $meta_ids = $stmt->get_result()->fetch_row()[0];
        $stmt->close();
        if ( empty( $meta_ids ) ) {
            return add_user_metadata( $user_id, $raw_meta_key, $passed_value );
        }

        $_meta_value = $meta_value;
        $meta_value = maybe_serialize( $meta_value );

        $stmt = $mysqli->prepare("UPDATE $table SET meta_value=? WHERE meta_key=? AND user_id=?");
        $stmt->bind_param("ssi", $meta_value, $meta_key, $user_id);
        $stmt->execute();
        $ok = $stmt->affected_rows > 0;
        $stmt->close();

        return $ok;
}



function username_exists( $username ) {
    global $mysqli, $table_prefix;

    $table = $table_prefix.'users';

    $stmt = $mysqli->prepare("SELECT ID FROM $table WHERE user_login = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $arr = $stmt->get_result()->fetch_row()[0];
    if(!$arr) return false;
    $stmt->close();
    return true;
}

function email_exists( $email ) {
    global $mysqli, $table_prefix;

    $table = $table_prefix.'users';

    $stmt = $mysqli->prepare("SELECT ID FROM $table WHERE user_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $arr = $stmt->get_result()->fetch_row()[0];
    if(!$arr) return false;
    $stmt->close();
    return true;
}

function nicename_exists( $user_nicename, $user_login ) {
    global $mysqli, $table_prefix;

    $table = $table_prefix.'users';

    $stmt = $mysqli->prepare("SELECT ID FROM $table WHERE user_nicename = ? AND user_login != ? LIMIT 1");
    $stmt->bind_param("ss", $user_nicename, $user_login);
    $stmt->execute();
    $arr = $stmt->get_result()->fetch_row()[0];
    if(!$arr) return false;
    $stmt->close();
    return true;
}


function sanitize_user( $username ) {
        $username = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $username );
        $username = trim( strip_tags($username) );
        $username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username ); // Kill octets
        $username = preg_replace( '/&.+?;/', '', $username ); // Kill entities
        $username = preg_replace( '|[^a-z0-9 _.\-@]|i', '', $username );
        $username = preg_replace( '|\s+|', ' ', $username ); // Consolidate contiguous whitespace
        $username = trim( $username );

        return $username;
}

function wp_hash_password($password) {
        global $wp_hasher;

        if ( empty($wp_hasher) ) {
                require_once( ABSPATH.'wp-includes/class-phpass.php' );
                // By default, use the portable hash from phpass
                $wp_hasher = new PasswordHash(8, true);
        }

        return $wp_hasher->HashPassword( trim( $password ) );
}

function insert_user_data($data){
    global $mysqli, $table_prefix;
    $users_table = $table_prefix.'users';

    $data = array_map(function ($val) { return $val ? "'".$val."'" : "''";}, $data);
    $fields  = '`' . implode( '`, `', array_keys( $data ) ) . '`';
    $values = implode( ', ', $data );
    //$values = rtrim(str_repeat('?,',count($data)),',');
    $sql = "INSERT INTO `$users_table` ($fields) VALUES ($values)";
    $stmt = $mysqli->prepare($sql);
    //$types = str_repeat('s', count($data));
    //$stmt->bind_param($types, ...array_values($data));
    $stmt->execute();
    // $stmt->get_result();
    // $result = $stmt->get_result()->fetch_row()[0];
    // if(!$result) exit('insert_user_data failed');
    $stmt->close();

    return $mysqli->insert_id;
}


function wp_insert_user( $userdata, $user_level=10, $role='administrator' ) {
    global $table_prefix;

    $user_login = sanitize_user( $userdata['user_login'] );
    $user_pass = wp_hash_password( $userdata['user_pass'] );

    // user_login must be between 0 and 60 characters.
    if ( empty( $user_login ) ) {
        throw new Exception('empty_user_login' . ' Cannot create a user with an empty login name.');
    } elseif ( mb_strlen( $user_login ) > 60 ) {
        throw new Exception( 'user_login_too_long' . ' Username may not be longer than 60 characters.' );
    }

    if ( username_exists( $user_login ) ) {
        throw new Exception( 'existing_user_login' . ' Sorry, that username already exists!' );
    }

    $display_name = $userdata['display_name'] ?: $user_login;
    $user_nicename = mb_substr( $display_name.'-auto-generated', 0, 50 ); // Best security practices recommend the nicename be different from the username
    $user_url = empty( $userdata['user_url'] ) ? '' : $userdata['user_url'];
    $user_email = empty( $userdata['user_email'] ) ? '' : $userdata['user_email'];

    if ( email_exists( $user_email ) ) {
        throw new Exception( 'existing_user_email' . ' Sorry, that email address is already used!' );
    }

    // Store values to save in user meta.
    $meta = array();

    $nickname = $user_login;
    $meta['nickname'] = $nickname;

    $first_name = '';
    $meta['first_name'] = $first_name;

    $last_name = '';
    $meta['last_name'] = $last_name;

    $description = '';
    $meta['description'] = $description;

    $meta['rich_editing'] = 'true';
    $meta['syntax_highlighting'] = 'true';
    $meta['comment_shortcuts'] = 'false';
    $admin_color = 'fresh';
    $meta['admin_color'] = preg_replace( '|[^a-z0-9 _.\-@]|i', '', $admin_color );
    $meta['use_ssl'] = empty( $userdata['use_ssl'] ) ? 0 : $userdata['use_ssl'];
    $user_registered = empty( $userdata['user_registered'] ) ? gmdate( 'Y-m-d H:i:s' ) : $userdata['user_registered'];
    $meta['show_admin_bar_front'] = 'true';
    $meta['locale'] = '';
    if (nicename_exists($user_nicename, $user_login)){
        throw new Exception( 'existing_user_nicename' . ' Sorry, that nicename is already used!' );
    }
    $compacted = compact( 'user_pass', 'user_email', 'user_url', 'user_nicename', 'display_name', 'user_registered' );
    $data = wp_unslash( $compacted );
    $data = $data + compact( 'user_login' );

    // $field_processor = new process_fields();
    // $data = $field_processor->process_fields('users', $data, null);
    //$user_id = (int) insert_user_data($data );

    $user_id = (int)insert_user_data( $data );

    // Update user meta.
    foreach ( $meta as $key => $value ) {
        add_user_metadata( $user_id, $key, $value );
    }

    $caps[$role] = true;
    add_user_metadata( $user_id, $table_prefix.'capabilities', $caps );
    add_user_metadata( $user_id, $table_prefix.'user_level', $user_level );

    return $user_id;
}
