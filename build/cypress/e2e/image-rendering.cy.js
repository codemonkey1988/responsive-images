/// <reference types="cypress" />

/*
 * Using element.naturalWidth and element.naturalHeight does not work because
 * they do not show the intrinsic image size.
 * Checking element.currentSrc is also a problem, because chrome seems not to
 * strictly use the given configuration (chrome uses way larger images than needed with srcset)
 */

describe('A website user visits ', () => {

  it('the page with bootstrap configuration and picture tag rendering', () => {
    cy.visit('/bootstrap')

    cy.get('body > picture > source').should('have.length', 2)
    cy.get('body > picture > source').should('have.attr', 'media')
    cy.get('body > picture > source').should('have.attr', 'srcset')
    cy.get('body > picture > source').should('have.attr', 'sizes')
    cy.get('body > picture > source').each(($item) => {
      expect($item.attr('srcset').split(',').length).to.be.equal(7)
      expect($item.attr('sizes').split(',').length).to.be.equal(4)
    })

    cy.get('body > picture > img').should('be.visible')
    cy.get('body > picture > img').should('have.attr', 'class', 'image-bootstrap')
  })

  it('the page with default configuration and picture tag rendering', () => {
    cy.visit('/default')

    cy.get('body > picture > source').should('have.length', 2)
    cy.get('body > picture > source').should('have.attr', 'media')
    cy.get('body > picture > source').should('have.attr', 'srcset')
    cy.get('body > picture > source').should('have.attr', 'sizes')
    cy.get('body > picture > source').each(($item) => {
      expect($item.attr('srcset').split(',').length).to.be.equal(7)
      expect($item.attr('sizes').split(',').length).to.be.equal(2)
    })

    cy.get('body > picture > img').should('be.visible')
    cy.get('body > picture > img').should('have.attr', 'class', 'image-default')
  })

  it('the page with default configuration and srcset rendering', () => {
    cy.visit('/srcset-rendering')

    cy.get('body > img').should('be.visible')
    cy.get('body > img').should('have.attr', 'srcset')
    cy.get('body > img').should('have.attr', 'class', 'image-srcset')
    cy.get('body > img')
      .and(($img) => {
        expect($img.attr('srcset').split(',').length).to.be.equal(7)
      })
  })

});
