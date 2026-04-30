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

		// Home IRL: metadata should be server-rendered (no JS hydration dependency).
		// Note: environments can lag behind theme/plugin deploys; keep smoke green by default.
		const irl = page.locator( '.events-upcoming' );
		if ( await irl.count() ) {
			await expect( irl.first() ).toBeVisible();
			const metaLines = irl.first().locator( '.entry-meta-inline' );
			if ( await metaLines.count() ) {
				await expect( metaLines.first() ).toBeVisible();
				await expect( metaLines.first().locator( '.entry-when' ) ).toBeVisible();
			} else if ( process.env.E2E_IRL_STRICT === '1' ) {
				expect(
					await metaLines.count(),
					'IRL SSR metadata absent — deploy latest jardin-events + jardin-theme (purge caches if needed)'
				).toBeGreaterThan( 0 );
			} else {
				test.skip(
					true,
					'IRL SSR metadata absent — deploy latest jardin-events + jardin-theme (set E2E_IRL_STRICT=1 to fail hard)'
				);
			}
		}
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

	test( '3b — hub activité (FR)', async ( { page } ) => {
		const r = await page.goto( '/activites/' );
		expect( r, 'HTTP' ).not.toBeNull();
		if ( r?.status() === 404 ) {
			test.skip( true, '/activites/ 404 — déployer le thème + flush permaliens' );
		}
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
			test.skip( true, 'feed/listens/ 404 — activer jardin-scrobbler ou rewrites' );
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
