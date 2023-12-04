<?php

namespace PS_Attestation;

require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

class Main {
	use Singleton;

	protected function init() {
        // Activation/Deactivation hook.
		register_activation_hook( PS_ATTESTATION_DIR . '/ps_attestation.php', [$this, 'ps_attestation_activation'] );
        register_deactivation_hook( PS_ATTESTATION_DIR . '/ps_attestation.php', [$this, 'ps_attestation_deactivation'] );

        // Hooks registration.
        add_action( 'init', [$this, 'ps_attestation_register_path'] );
        add_action( 'template_redirect', [$this, 'ps_attestation_serve_attestation_file'] );
        add_action( 'redirect_canonical', [$this, 'ps_attestation_disable_canonical_redirects'], 10, 2 );
	}

    
    /**
     *  Writes a new permalink entry on plugin activation.
     */
    function ps_attestation_activation() {
        ps_attestation_register_path();
        flush_rewrite_rules();
    }
    
    /**
     * Cleans the permalink structure on plugin deactivation.
     */
    function ps_attestation_deactivation() {
        flush_rewrite_rules();
    }

    /**
     * Registers the permalink path.
     */
    function ps_attestation_register_path() {
        add_rewrite_tag( '%serve-attestation%', '.well-known/(privacy-sandbox-attestations.json)' );
        add_permastruct( 'attestation', '/%serve-attestation%' );
    }

    /**
     * Serve the attestations file.
     */
    function ps_attestation_serve_attestation_file() {
        if ($query_var = get_query_var('serve-attestation')) {
            header("Content-Type: application/json");
            $path = PS_ATTESTATION_DIR . '/privacy-sandbox-attestations.json';
            $filesystem = new \WP_Filesystem_Direct( true );
            $result = $filesystem->get_contents($path);
            echo $result;
            exit;
        }
    }

    /** 
     * Disable slash at the URL end.
     */
    function ps_attestation_disable_canonical_redirects( $redirect_url, $requested_url ) {
        if ( preg_match( '|privacy-sandbox-attestations\.json|', $requested_url ) ) {
            return $requested_url;
        }
        return $redirect_url;
    }
    
}