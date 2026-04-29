// @ts-check
const { defineConfig, devices } = require( '@playwright/test' );
const path = require( 'path' );
require( 'dotenv' ).config( { path: path.resolve( __dirname, '../.env' ) } );

const base = process.env.E2E_BASE_URL || 'https://dev.jasonrouet.com';
const hasBasic =
	process.env.E2E_HTTP_USER &&
	process.env.E2E_HTTP_PASS;
const extraHeaders = hasBasic
	? {
		Authorization:
			'Basic ' +
			Buffer.from(
				process.env.E2E_HTTP_USER + ':' + process.env.E2E_HTTP_PASS
			).toString( 'base64' ),
	}
	: undefined;

/**
 * @see jardin-docs/tests-strategy.md
 */
module.exports = defineConfig( {
	testDir: path.join( __dirname, 'specs' ),
	forbidOnly: !! process.env.CI,
	retries: process.env.CI ? 1 : 0,
	workers: process.env.CI ? 2 : undefined,
	reporter: 'html',
	use: {
		baseURL: base.replace( /\/$/, '' ),
		extraHTTPHeaders: extraHeaders,
		trace: 'on-first-retry',
		ignoreHTTPSErrors: true,
	},
	projects: [
		{
			name: 'chromium',
			use: { ...devices[ 'Desktop Chrome' ] },
		},
	],
} );
