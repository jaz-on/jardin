// @ts-check
const { test, expect } = require( '@playwright/test' );

const skipEn = process.env.E2E_SKIP_EN === '1';
const enPath = ( process.env.E2E_EN_PATH || '/en/' ).replace( /\/$/, '' ) + '/';

test.describe( 'Phase 5 — 7 parcours critiques (smoke HTTP)', () => {
	test( '1 — accueil', async ( { page } ) => {
		const r = await page.goto( '/' );
		expect( r, 'HTTP' ).not.toBeNull();
		expect( r?.status() ).toBe( 200 );
		const main = page.getByRole( 'main' ).or( page.locator( '#main' ) );
		await expect( main.first() ).toBeVisible();
	} );

	test( '2 — /journal/', async ( { page } ) => {
		const r = await page.goto( '/journal/' );
		expect( r, 'HTTP' ).not.toBeNull();
		expect( r?.status() ).toBe( 200 );
	} );

	test( "3 — /journal/ avec filtre d'essai (kind=til)", async ( { page } ) => {
		const r = await page.goto( '/journal/?kind=til' );
		expect( r, 'HTTP' ).not.toBeNull();
		expect( r?.status() ).toBe( 200 );
	} );

	test( '4 — flux /feed/', async ( { request } ) => {
		const r = await request.get( '/feed/' );
		expect( r.status() ).toBe( 200 );
		const ct = r.headers()[ 'content-type' ] || '';
		expect( ct, 'type RSS/Atom' ).toMatch( /xml|atom|rss/ );
	} );

	test( '5 — flux /feed/listens/', async ( { request } ) => {
		const r = await request.get( '/feed/listens/' );
		// 404 possible si le plugin n’est pas actif sur l’environnement de test.
		if ( r.status() === 404 ) {
			test.skip( true, 'feed/listens/ 404 — activer scrobble-journal ou rewrites' );
		}
		expect( r.status() ).toBe( 200 );
		const ct = r.headers()[ 'content-type' ] || '';
		expect( ct, 'type RSS' ).toMatch( /xml|rss/ );
	} );

	test( '6 — 404 propre', async ( { page } ) => {
		const r = await page.goto( '/cette-page-nexiste-pas-jardin-e2e/' );
		expect( r?.status() ).toBe( 404 );
		const body = page.locator( 'body' );
		await expect( body ).toBeVisible();
	} );

	test( '7 — accueil EN (Polylang)', async ( { page } ) => {
		test.skip( skipEn, 'E2E_SKIP_EN=1' );
		const r = await page.goto( enPath, { waitUntil: 'domcontentloaded' } );
		expect( r, 'page EN' ).not.toBeNull();
		expect( r?.status() ).toBe( 200 );
	} );
} );
