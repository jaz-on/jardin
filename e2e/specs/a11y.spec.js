// @ts-check
const { test, expect } = require( '@playwright/test' );
const { AxeBuilder } = require( '@axe-core/playwright' );

const paths = [ '/', '/journal/' ];

for ( const p of paths ) {
	test( `a11y — axe (serious|critical) sur ${ p }`, async ( { page, baseURL } ) => {
		if ( ! baseURL ) {
			test.skip( true, 'E2E_BASE_URL manquante' );
		}
		const url = new URL( p, baseURL ).toString();
		await page.goto( url, { waitUntil: 'load' } );
		const res = await new AxeBuilder( { page } )
			.withTags( [ 'wcag2a', 'wcag2aa' ] )
			.analyze();
		const strict = process.env.E2E_A11Y_STRICT === '1';
		const bad = res.violations.filter( ( v ) =>
			'critical' === v.impact || ( strict && 'serious' === v.impact )
		);
		if ( ! strict ) {
			const ser = res.violations.filter( ( v ) => 'serious' === v.impact );
			if ( ser.length ) {
				// Not blocking until palette pass (roadmap phase 6).
				// Run with E2E_A11Y_STRICT=1 to fail on serious+.
				console.warn( `[a11y] ${ p } : ${ String( ser.length ) } serious (non-bloquant sans E2E_A11Y_STRICT=1)` );
			}
		}
		expect( bad, JSON.stringify( bad, null, 2 ) ).toEqual( [] );
	} );
}
