# AldafluxOvhBundle



## aldaflux_ovh.yaml

```
aldaflux_ovh:
    credentials:
        endpoint_name:      "ovh-eu"
        application_key:    '%env(resolve:OVH_APPLICATION_KEY)%'
        application_secret: '%env(resolve:OVH_APPLICATION_SECRET)%'
        consumer_key:       '%env(resolve:OVH_CONSUMER_KEY)%'
```
Optional : 
```
aldaflux_ovh:
    default:
        ip: '%env(resolve:CURRENT_IP)%'
        domain: '%env(resolve:DEFAULT_DOMAIN)%'
```

