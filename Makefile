########## CONFIGURATION ##########

# Helper functions

COMPILE_MSG = @printf "Sources: $^\n--> $@\n\n"

# Paths

VENDOR_DIR := vendor
TESTS_DIR := tests
DOC_DIR := doc
SRC_DIR := src
COMPOSER := ./composer.phar
PHPUNIT = $(VENDOR_DIR)/bin/phpunit --testdox
PHPDOC := phpDocumentor.phar

# Assemble inputs

DOC_INPUTS = $(shell find $(SRC_DIR) -type f -name '*.php')

# Assemble outputs

DOC_OUTPUT = $(DOC_DIR)/index.html

OUTPUTS = $(VENDOR_DIR) $(DOC_OUTPUT)


########## RULES ##########

# Top level

.PHONY: all test doc dep

all: $(OUTPUTS)
	@echo > /dev/null

doc: $(DOC_OUTPUT)
	@echo > /dev/null

test: $(VENDOR_DIR)
	$(PHPUNIT) $(TESTS_DIR)

dep: $(VENDOR_DIR)
	@echo > /dev/null

# Composer modules

$(VENDOR_DIR): composer.json composer.phar
	$(COMPILE_MSG)
	@rm -rf $(VENDOR_DIR)/*
	@$(COMPOSER) install 
	@$(COMPOSER) dump-autoload

composer.phar: install_composer
	$(COMPILE_MSG)
	@php install_composer

# PHPDoc

$(DOC_OUTPUT): $(DOC_INPUTS) $(PHPDOC)
	$(COMPILE_MSG)
	@mkdir -p $(@D)
	@./$(PHPDOC) -d $(<D) -t $(@D)

$(PHPDOC):
	$(COMPILE_MSG)
	@wget https://phpdoc.org/phpDocumentor.phar
	@chmod +x phpDocumentor.phar
