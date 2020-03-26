#put here your commands
while getopts ":i:N:a:d:e:E:W:j:" option; do
    case "${option}" in
        v)
            v=${OPTARG}
            ;;
        g)
            g=${OPTARG}
            ;;
        *)
            usage
            ;;
    esac
done
shift $((OPTIND-1))
if [ -z "${v}" ] || [ -z "${g}" ]; then
    usage
fi
echo "v = ${v}"
echo "g = ${g}"